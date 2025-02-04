<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'LoginController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Auth Route
$route['login'] = 'LoginController/index';             
$route['login/login_aksi'] = 'LoginController/login_aksi'; 
$route['logout'] = 'LoginController/logout';          
$route['register'] = 'RegisterController/index';       
$route['register/register_aksi'] = 'RegisterController/register_aksi'; 

//Dashboard Route
$route['AppController/dashboard'] = 'DashboardController/index'; 
$route['dashboard'] = 'DashboardController/index';

//Master Data User Route
$route['user/index'] = 'UserController/index';
$route['user/index/(:num)'] = 'UserController/index/$1';
$route['user/detail/(:num)'] = 'UserController/detail/$1'; 
$route['user/edit/(:num)'] = 'UserController/edit/$1';     
$route['user/delete/(:num)'] = 'UserController/delete/$1'; 
$route['user/update'] = 'UserController/update';

//Master Data Provinsi Route
$route['provinsi/index'] = 'ProvinsiController/index';
$route['provinsi/index/(:num)'] = 'ProvinsiController/index/$1';
$route['provinsi/tambah'] = 'ProvinsiController/tambah';
$route['provinsi/detail/(:num)'] = 'ProvinsiController/detail/$1'; 
$route['provinsi/edit/(:num)'] = 'ProvinsiController/edit/$1';     
$route['provinsi/delete/(:num)'] = 'ProvinsiController/delete/$1'; 
$route['provinsi/update/(:num)'] = 'ProvinsiController/update/$1';
$route['provinsi/show_entries'] = 'ProvinsiController/show_entries';

//Master Data Kabupaten Route
$route['kabupaten/index'] = 'KabupatenController/index';
$route['kabupaten/index/(:num)'] = 'KabupatenController/index/$1';
$route['kabupaten/tambah'] = 'KabupatenController/tambah';
$route['kabupaten/detail/(:num)'] = 'KabupatenController/detail/$1'; 
$route['kabupaten/edit/(:num)'] = 'KabupatenController/edit/$1';     
$route['kabupaten/delete/(:num)'] = 'KabupatenController/delete/$1'; 
$route['kabupaten/update/(:num)'] = 'KabupatenController/update/$1';
$route['kabupaten/show_entries'] = 'KabupatenController/show_entries';

//Master Data Kecamatan Route
$route['kecamatan/index'] = 'KecamatanController/index';
$route['kecamatan/index/(:num)'] = 'KecamatanController/index/$1';
$route['kecamatan/tambah'] = 'KecamatanController/tambah';
$route['kecamatan/detail/(:num)'] = 'KecamatanController/detail/$1'; 
$route['kecamatan/edit/(:num)'] = 'KecamatanController/edit/$1';     
$route['kecamatan/delete/(:num)'] = 'KecamatanController/delete/$1'; 
$route['kecamatan/update/(:num)'] = 'KecamatanController/update/$1';
$route['kecamatan/show_entries'] = 'KecamatanController/show_entries';

//Master Data Kelurahan Route
$route['kelurahan/index'] = 'KelurahanController/index';
$route['kelurahan/index/(:num)'] = 'KelurahanController/index/$1';
$route['kelurahan/tambah'] = 'KelurahanController/tambah';
$route['kelurahan/detail/(:num)'] = 'KelurahanController/detail/$1'; 
$route['kelurahan/edit/(:num)'] = 'KelurahanController/edit/$1';     
$route['kelurahan/delete/(:num)'] = 'KelurahanController/delete/$1'; 
$route['kelurahan/update/(:num)'] = 'KelurahanController/update/$1';
$route['kelurahan/show_entries'] = 'KelurahanController/show_entries';

//Master Data Puskesmas Route
$route['puskesmas/index'] = 'PuskesmasController/index';
$route['puskesmas/index/(:num)'] = 'PuskesmasController/index/$1';
$route['puskesmas/tambah'] = 'PuskesmasController/tambah';
$route['puskesmas/detail/(:num)'] = 'PuskesmasController/detail/$1'; 
$route['puskesmas/edit/(:num)'] = 'PuskesmasController/edit/$1';     
$route['puskesmas/delete/(:num)'] = 'PuskesmasController/delete/$1'; 
$route['puskesmas/update/(:num)'] = 'PuskesmasController/update/$1';
$route['puskesmas/show_entries'] = 'PuskesmasController/show_entries';

//Pemekaran Kabupaten Route
$route['pkabupaten/index'] = 'PkabupatenController/index';
$route['pkabupaten/index/(:num)'] = 'PkabupatenController/index/$1';
$route['pkabupaten/tambah'] = 'PkabupatenController/tambah';
$route['pkabupaten/detail/(:num)'] = 'PkabupatenController/detail/$1';  
$route['pkabupaten/edit/(:num)'] = 'PkabupatenController/edit/$1';     
$route['pkabupaten/delete/(:num)'] = 'PkabupatenController/delete/$1'; 
$route['pkabupaten/update/(:num)'] = 'PkabupatenController/update/$1';
$route['pkabupaten/show_entries'] = 'PkabupatenController/show_entries';

//Pemekaran Kecamatan Route
$route['pkecamatan/index'] = 'PkecamatanController/index';
$route['pkecamatan/index/(:num)'] = 'PkecamatanController/index/$1';
$route['pkecamatan/tambah'] = 'PkecamatanController/tambah';
$route['pkecamatan/detail/(:num)'] = 'PkecamatanController/detail/$1';  
$route['pkecamatan/edit/(:num)'] = 'PkecamatanController/edit/$1';     
$route['pkecamatan/delete/(:num)'] = 'PkecamatanController/delete/$1'; 
$route['pkecamatan/update/(:num)'] = 'PkecamatanController/update/$1';
$route['pkecamatan/show_entries'] = 'PkecamatanController/show_entries';

//Pemekaran Kelurahan Route
$route['pkelurahan/index'] = 'PkelurahanController/index';
$route['pkelurahan/index/(:num)'] = 'PkelurahanController/index/$1';
$route['pkelurahan/tambah'] = 'PkelurahanController/tambah';
$route['pkelurahan/detail/(:num)'] = 'PkelurahanController/detail/$1';  
$route['pkelurahan/edit/(:num)'] = 'PkelurahanController/edit/$1';     
$route['pkelurahan/delete/(:num)'] = 'PkelurahanController/delete/$1'; 
$route['pkelurahan/update/(:num)'] = 'PkelurahanController/update/$1';
$route['pkelurahan/show_entries'] = 'PkelurahanController/show_entries';

//Pemekaran Puskesmas Route
$route['ppuskes/index'] = 'PkesController/index';
$route['ppuskes/index/(:num)'] = 'PkesController/index/$1';
$route['ppuskes/tambah'] = 'PkesController/tambah';
$route['ppuskes/detail/(:num)'] = 'PkesController/detail/$1';  
$route['ppuskes/edit/(:num)'] = 'PkesController/edit/$1';     
$route['ppuskes/delete/(:num)'] = 'PkesController/delete/$1'; 
$route['ppuskes/update/(:num)'] = 'PkesController/update/$1';
$route['ppuskes/show_entries'] = 'PkesController/show_entries';

