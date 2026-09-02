<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'Login';
$route['404_override'] = 'Maintance/notfound';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = "login/admin";
$route['dosen'] = "login";
$route['mahasiswa'] = "login";

// User Management
$route['user'] = "user/index";
$route['user/tambah'] = "user/tambah";
$route['user/simpan'] = "user/simpan";
$route['user/edit/(:num)'] = "user/edit/$1";
$route['user/ubah_data'] = "user/ubah_data";
$route['user/ubah_password'] = "user/ubah_password";
$route['user/hapus/(:num)'] = "user/hapus/$1";

// API Tokens Management
$route['admin/pengaturan/api_tokens'] = 'admin/pengaturan/Api_tokens/index';
$route['admin/pengaturan/api_tokens/tambah'] = 'admin/pengaturan/Api_tokens/tambah';
$route['admin/pengaturan/api_tokens/simpan'] = 'admin/pengaturan/Api_tokens/simpan';
$route['admin/pengaturan/api_tokens/edit/(:num)'] = 'admin/pengaturan/Api_tokens/edit/$1';
$route['admin/pengaturan/api_tokens/update'] = 'admin/pengaturan/Api_tokens/update';
$route['admin/pengaturan/api_tokens/hapus/(:num)'] = 'admin/pengaturan/Api_tokens/hapus/$1';
$route['admin/pengaturan/api_tokens/toggle/(:num)'] = 'admin/pengaturan/Api_tokens/toggle_status/$1';
$route['admin/pengaturan/api_tokens/generate_token'] = 'admin/pengaturan/Api_tokens/generate_token';
$route['admin/pengaturan/api_tokens/sinkronisasi/(:num)'] = 'admin/pengaturan/Api_tokens/sinkronisasi/$1';
$route['admin/pengaturan/api_tokens/sync_process/(:num)'] = 'admin/pengaturan/Api_tokens/sync_process/$1';
$route['admin/pengaturan/api_tokens/sync_start/(:num)'] = 'admin/pengaturan/Api_tokens/sync_start/$1';
$route['admin/pengaturan/api_tokens/sync_page/(:num)/(:num)'] = 'admin/pengaturan/Api_tokens/sync_page/$1/$2';
$route['admin/pengaturan/api_tokens/sync_finish/(:num)'] = 'admin/pengaturan/Api_tokens/sync_finish/$1';
$route['admin/pengaturan/api_tokens/get_log_detail/(:num)'] = 'admin/pengaturan/Api_tokens/get_log_detail/$1';
$route['admin/pengaturan/api_tokens/get_logs/(:num)'] = 'admin/pengaturan/Api_tokens/get_logs/$1';
$route['admin/pengaturan/distribusi_perwalian'] = 'admin/pengaturan/Distribusi_perwalian/index';
$route['admin/pengaturan/distribusi_perwalian/proses'] = 'admin/pengaturan/Distribusi_perwalian/proses';
$route['admin/pengaturan/distribusi_perwalian/preview'] = 'admin/pengaturan/Distribusi_perwalian/preview';
