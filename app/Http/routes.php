<?php

//Route::get('test', 'SmsController@test');
//Route::post('testEmail', 'EmailController@emailSend');
Route::post('resetPassword', 'SmsController@resetPassword');//重置密码
Route::post('bindPhone', 'SmsController@bindPhone');//手机绑定
Route::post('verifyEmail', 'EmailController@verifyEmail');//邮件验证
Route::post('bindEmail', 'EmailController@bindEmail');//邮箱绑定
Route::post('user/idCardVerify', 'UserController@idCardVerify');//身份验证

Route::get('self', 'UserController@self');
Route::post('users/{id}', 'UserController@update');//更新
Route::get('users/{id}', 'UserController@show');
// Route::post('user', 'UserController@store');
Route::get('users/{id}/resumes', 'ResumeController@get');//获取简历
//Route::get('resume/photo', 'ResumeController@photo');
Route::delete('users/{id}/resumes/{resumeId}', 'ResumeController@delete');//建立删除
Route::post('users/{id}/resumes', 'ResumeController@add');//添加简历
Route::post('users/{id}/resumes/{resumeId}', 'ResumeController@update');//更新简历
Route::get('users/{id}/orders', 'OrderController@query');//
//Route::post('avatar', 'AuthenticateController@updateAvatar');
Route::get('users/{id}/realNameApplies', 'UserController@getRealNameApplies');
Route::post('users/{id}/realNameApplies', 'UserController@createRealNameApplies');
Route::delete('users/{id}/realNameApplies/{rnaid}', 'UserController@deleteRealNameApply');
Route::get('users/{id}/logs', 'UserController@getLogs');
Route::get('userGetOrder','UserController@user_get_order');
Route::get('companyGetOrder','UserController@company_get_order');
Route::get('jobs', 'JobController@query');
Route::get('job/apply', 'UserController@getJobApply');         // use [GET] users/{id}/orders instead
Route::get('job/completed', 'UserController@getJobCompleted'); // use [GET] users/{id}/orders instead
Route::get('jobs/{id}', 'JobController@get')->where('id', '[0-9]+');
Route::post('jobs/{id}', 'JobController@update')->where('id', '[0-9]+');
Route::delete('jobs/{id}', 'JobController@delete')->where('id', '[0-9]+');
Route::post('jobs/{id}/apply', 'JobController@apply')->where('id', '[0-9]+');
Route::post('job/apply', 'UserController@postJobApply');       // use [POST] jobs/{id}/apply instead
Route::get('jobs/{id}/evaluate', 'JobController@getEvaluate');
Route::post('job/evaluate', 'UserController@postJobEvaluate'); // use [POST] orders/{id}/evaluate instead
Route::post('expect_jobs', 'ExpectJobController@create');

Route::get('expect_jobs', 'ExpectJobController@query');
Route::get('expect_jobs/{id}', 'ExpectJobController@get')->where('id', '[0-9]+');
Route::post('expect_jobs/{id}', 'ExpectJobController@update')->where('id', '[0-9]+');
Route::delete('expect_jobs/{id}', 'ExpectJobController@delete')->where('id', '[0-9]+');
Route::post('expect_jobs/{id}/apply', 'ExpectJobController@apply');
Route::get('getAllJob', 'UserController@mainPage');

Route::get('companies', 'CompanyController@query');
Route::get('companies/{id}', 'CompanyController@get')->where('id', '[0-9]+');
Route::post('companies/{id}', 'CompanyController@update')->where('id', '[0-9]+');
Route::get('companies/apply', 'CompanyController@getApply');
Route::post('companies/apply', 'CompanyController@postApply');

Route::get('releaseJob','CompanyController@releaseJob');


Route::delete('orders/{id}', 'OrderController@close')->where('id', '[0-9]+');
Route::get('orders/{id}', 'OrderController@get')->where('id', '[0-9]+');
Route::get('orders/{id}/evaluate', 'OrderController@getEvaluate')->where('id', '[0-9]+');
Route::post('orders/{id}/evaluate', 'OrderController@postEvaluate')->where('id', '[0-9]+');

Route::get('umsg', 'MessageController@getUpdate');
Route::get('messages', 'MessageController@get');
Route::get('notifications/{id}', 'MessageController@getNotification')->where('id', '[0-9]+');
Route::get('conversations', 'MessageController@getConversation');
Route::post('conversations', 'MessageController@postConversation');
Route::post('feedbacks', 'MessageController@postFeedback');
Route::get('banners', 'DataController@getBanners');

// 需要限制次数的请求
// 每分钟三次
Route::group(['middleware' => 'throttle:3'], function ($api) {
    Route::post('auth', 'AuthenticateController@emailAuth');
    Route::post('authPhone', 'AuthenticateController@phoneAuth');
    Route::get('refresh', 'AuthenticateController@refreshToken');

    Route::post('register', 'AuthenticateController@register');
    Route::post('registerByPhone', 'SmsController@registerByPhone');

});
Route::post('upload/image', 'UploadController@uploadImage');

// 每分钟一次
Route::group(['middleware' => 'throttle:1'], function ($api) {
//    Route::get('getSmsCode', 'SmsController@getSmsCode');
});

// 每分钟两次
Route::group(['middleware' => 'throttle:2'], function ($api) {
    Route::get('getSmsCode', 'SmsController@getSmsCode');
    Route::post('sendVerifyEmail', 'EmailController@sendVerifyEmail');
});

// boss
Route::group(['namespace' => 'BOSS', 'middleware' => ['jwt.auth', 'role:admin']], function () {
    Route::get('users', 'UserController@query');
    Route::post('notifications', 'MessageController@postNotifications');
    Route::get('real_name_applies', 'UserController@getAllRealNameApplies');
    Route::get('notifications/history', 'MessageController@getHistory');
    Route::get('company_applies', 'UserController@getAllCompanyApplies');
    Route::get('orders', 'UserController@getOrders');
    Route::get('feedbacks', 'MessageController@getFeedbacks');
    Route::post('data', 'DataController@setData');
    Route::post('feedbacks/{id}', 'MessageController@updateFeedback');
    Route::post('real_name_applies/{id}', 'UserController@updateRealNameApply')->where('id', '[0-9]+');
    Route::post('company_applies/{id}', 'UserController@updateCompanyApply')->where('id', '[0-9]+');
    Route::get('reports', 'MessageController@getReports');
    Route::post('reports/{id}', 'MessageController@updateReport');
    Route::post('users/{id}/role', 'UserController@updateRole');
    Route::post('jobs/{id}/restore', 'JobController@restore');
    Route::post('expect_jobs/{id}/restore', 'ExpectJobController@restore');
});

Route::get('/', function () {
    return view('welcome');
});
