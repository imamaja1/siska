<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Pdf library - Dompdf wrapper
 * Drop-in replacement for M_pdf (mPDF) to fix mkdir(): Permission denied on tempDir.
 *
 * Compatible API: reinitialize(), SetHTMLHeader(), SetHeader(), WriteHTML(), Output()
 * Real dompdf API also available: loadHtml(), setPaper(), render(), stream()
 */
class Pdf
{
    /** @var Dompdf */
    private $dompdf;

    /** @var Options */
    private $options;

    private $headerHtml = '';
    private $htmlContent = '';
    private $paper = 'A4';
    private $orientation = 'portrait';
    private $margins = [
        'left' => 15,
        'right' => 15,
        'top' => 38,
        'bottom' => 20,
        'header' => 5,
        'footer' => 5,
    ];

    private $paperSizes = [
        'folio' => [0, 0, 595.28, 935.43], // 210 x 330 mm
        'legal' => [0, 0, 612.00, 1008.00], // 8.5 x 14 inch
        'a4'    => 'A4',
        'letter'=> 'letter',
    ];

    public function __construct($param = null)
    {
        $this->initDompdf($param);
    }

    public function reinitialize($param)
    {
        $this->headerHtml = '';
        $this->htmlContent = '';
        $this->initDompdf($param);
    }

    private function initDompdf($param)
    {
        // parse mpdf-style param array
        if (is_array($param)) {
            if (isset($param['format'])) {
                $this->paper = strtolower($param['format']);
            }
            if (isset($param['orientation'])) {
                $this->orientation = $param['orientation'];
            }
            // margins in mm
            foreach (['margin_left','margin_right','margin_top','margin_bottom','margin_header','margin_footer'] as $k) {
                $short = str_replace('margin_','',$k);
                if (isset($param[$k])) {
                    $this->margins[$short] = (int)$param[$k];
                }
            }
        } elseif (is_string($param) && $param !== '') {
            // string format like "win-1252,Folio,15,15,38,20,5,5" - parse loosely
            $parts = explode(',', str_replace(["'", '"', ' '], '', $param));
            if (isset($parts[1]) && $parts[1] !== '') $this->paper = strtolower($parts[1]);
        }

        $tempDir = $this->resolveTempDir();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', false);
        $options->set('defaultFont', 'Helvetica');
        $options->set('tempDir', $tempDir);
        // chroot to allow file:// and relative paths inside FCPATH
        $options->set('chroot', FCPATH);

        $this->options = $options;
        $this->dompdf = new Dompdf($options);

        // set paper
        $paper = $this->resolvePaper($this->paper);
        $this->dompdf->setPaper($paper, $this->orientation);
    }

    private function resolveTempDir()
    {
        // Prefer application/cache/dompdf, fallback to sys_get_temp_dir()
        $candidates = [
            FCPATH . 'application/cache/dompdf',
            sys_get_temp_dir(),
        ];
        foreach ($candidates as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            if (is_dir($dir) && is_writable($dir)) {
                return $dir;
            }
        }
        return sys_get_temp_dir();
    }

    private function resolvePaper($format)
    {
        $format = strtolower(trim($format));
        if (isset($this->paperSizes[$format])) {
            return $this->paperSizes[$format];
        }
        // dompdf knows: 'A4','legal','letter','folio' (folio not native, use custom)
        // try return as-is, dompdf will fallback to A4 if unknown
        return $format;
    }

    // --- mPDF compatibility layer ---

    public function SetHTMLHeader($html)
    {
        $this->headerHtml = (string)$html;
    }

    // mPDF alias SetHeader (KRS uses this)
    public function SetHeader($html)
    {
        $this->headerHtml = (string)$html;
    }

    public function WriteHTML($html)
    {
        $this->htmlContent .= (string)$html;
    }

    /**
     * @param string $filename
     * @param string $dest D = download, I = inline, S = return string
     */
    public function Output($filename = 'document.pdf', $dest = 'I')
    {
        $fullHtml = $this->buildFullHtml($this->headerHtml, $this->htmlContent);
        $this->dompdf->loadHtml($fullHtml);

        // re-apply paper (in case reinitialize changed it after construct)
        $paper = $this->resolvePaper($this->paper);
        $this->dompdf->setPaper($paper, $this->orientation);

        $this->dompdf->render();

        $dest = strtoupper($dest);
        if ($dest === 'S') {
            return $this->dompdf->output();
        }

        $attachment = ($dest === 'D') ? 1 : 0;
        // dompdf stream() sends headers and exits - we use output + CI handling for cleaner control
        // but to keep compatible with existing code, use stream()
        // Prevent CI output buffering issues
        if (ob_get_length()) {
            // clean any previous output but keep it silent
            while (ob_get_level() > 0 && ob_get_length()) {
                @ob_end_clean();
            }
        }
        $this->dompdf->stream($filename, ['Attachment' => $attachment]);
        exit;
    }

    // --- native dompdf helpers (optional) ---

    public function loadHtml($html)
    {
        $this->htmlContent = (string)$html;
        $this->headerHtml = '';
    }

    public function loadHtmlWithHeader($header, $html)
    {
        $this->headerHtml = (string)$header;
        $this->htmlContent = (string)$html;
    }

    public function render()
    {
        $fullHtml = $this->buildFullHtml($this->headerHtml, $this->htmlContent);
        $this->dompdf->loadHtml($fullHtml);
        $paper = $this->resolvePaper($this->paper);
        $this->dompdf->setPaper($paper, $this->orientation);
        $this->dompdf->render();
    }

    public function stream($filename = 'document.pdf', $options = [])
    {
        $this->dompdf->stream($filename, $options);
        exit;
    }

    public function setPaper($paper, $orientation = 'portrait')
    {
        $this->paper = strtolower($paper);
        $this->orientation = $orientation;
        $resolved = $this->resolvePaper($this->paper);
        $this->dompdf->setPaper($resolved, $this->orientation);
    }

    public function getDompdf()
    {
        return $this->dompdf;
    }

    // Magic to silently ignore mPDF-only properties like defaultheaderline
    public function __set($name, $value) { /* ignore */ }
    public function __get($name) { return null; }

    private function buildFullHtml($header, $content)
    {
        // Inject @page margin based on mpdf config
        // mpdf margin_top includes header space; for dompdf we use margin_top as @page top
        $m = $this->margins;
        $pageCss = sprintf(
            '@page { margin: %dmm %dmm %dmm %dmm; } body { font-family: sans-serif; font-size: 8pt; margin: 0; }',
            $m['top'], $m['right'], $m['bottom'], $m['left']
        );

        // Normalize image src: convert http://domain/assets/... to local file path for chroot reliability
        // Dompdf isRemoteEnabled allows http, but file path avoids network/DNS and is faster
        $convertAssets = function($html) {
            return preg_replace_callback('#src=["\']https?://[^"\']+/assets/([^"\']+)["\']#i', function($m) {
                $local = FCPATH . 'assets/' . $m[1];
                // keep as file path inside chroot; dompdf will resolve it
                return 'src="' . $local . '"';
            }, $html);
        };
        $header = $convertAssets($header);
        $content = $convertAssets($content);
        // Also handle style from views - they already contain <html><head><style>...

        // If content already has <html>, inject style into <head>
        if (stripos($content, '<html') !== false || stripos($content, '<head') !== false) {
            // inject pageCss before </head>
            $injected = '<style>' . $pageCss . '</style>';
            if (stripos($content, '</head>') !== false) {
                $content = preg_replace('/<\/head>/i', $injected . '</head>', $content, 1);
            } else {
                $content = $injected . $content;
            }
            // prepend header inside <body> after <body> tag
            if ($header !== '') {
                if (preg_match('/<body[^>]*>/i', $content, $m2)) {
                    $content = preg_replace('/<body[^>]*>/i', $m2[0] . $header, $content, 1);
                } else {
                    $content = $header . $content;
                }
            }
            return $content;
        }

        // Fallback: wrap header+content in html document
        return '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><style>' . $pageCss . '</style></head><body>' . $header . $content . '</body></html>';
    }
}
