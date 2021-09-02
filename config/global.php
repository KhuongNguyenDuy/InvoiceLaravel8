<?php
/**
 * constant var
 */
return [
    'pagination_records' => 10, //paginate
    //'user_type' => ['User', 'Admin'],
    'tax' => 10, //tax cost

    'estimate_files_path' => storage_path('app/estimations/'),
    'order_files_path' => storage_path('app/orders/'),
    'invoice_files_path' => storage_path('app/invoices/')

    //How to call?
    //dd(config('global.tax'));
    //dd(config('global.pagination_records'));
]

?>
