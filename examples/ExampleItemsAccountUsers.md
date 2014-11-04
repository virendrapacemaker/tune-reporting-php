
make API_KEY=API_KEY examples

```bash
=========================================================
= Tune Management API Items Account Users               =
=========================================================
======================================================
 Fields of Account Users records.           
======================================================
Array
(
    [0] => account_id
    [1] => cell_phone
    [2] => created
    [3] => email
    [4] => first_name
    [5] => google_auth_enabled
    [6] => google_auth_verified
    [7] => id
    [8] => last_name
    [9] => modified
    [10] => name
    [11] => password
    [12] => phone
    [13] => signup_ip
    [14] => status
    [15] => time_zone
    [16] => title
)

======================================================
 Count Account Users records.                
======================================================
= TuneManagementResponse:
Tune\Management\Shared\Service\TuneManagementResponse Object
(
    [request_url:Tune\Management\Shared\Service\TuneManagementResponse:private] => https://api.mobileapptracking.com/v2/account/users/count?api_key=API_KEY
    [response_http_code:Tune\Management\Shared\Service\TuneManagementResponse:private] => 200
    [response_headers:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [0] => HTTP/1.1 200 OK
            [1] => Access-Control-Allow-Origin: *
            [2] => Content-Type: application/json
            [3] => Date: Tue, 04 Nov 2014 17:24:12 GMT
            [4] => Server: nginx
            [5] => Vary: Accept-Encoding
            [6] => Content-Length: 50
            [7] => Connection: keep-alive
        )

    [response_json:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [status_code] => 200
            [response_size] => 50
            [data] => 32
        )

)

= Count:32

======================================================
 Find Account Users records.                 
======================================================
= TuneManagementResponse:
Tune\Management\Shared\Service\TuneManagementResponse Object
(
    [request_url:Tune\Management\Shared\Service\TuneManagementResponse:private] => https://api.mobileapptracking.com/v2/account/users/find?api_key=API_KEY&fields=account_id%2Ccell_phone%2Ccreated%2Cemail%2Cfi
rst_name%2Cgoogle_auth_enabled%2Cgoogle_auth_verified%2Cid%2Clast_name%2Cmodified%2Cname%2Cpassword%2Cphone%2Csignup_ip%2Cstatus%2Ctime_zone%2Ctitle&limit=5&sort%5Bcreated%5D=DESC
    [response_http_code:Tune\Management\Shared\Service\TuneManagementResponse:private] => 200
    [response_headers:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [0] => HTTP/1.1 200 OK
            [1] => Access-Control-Allow-Origin: *
            [2] => Content-Type: application/json
            [3] => Date: Tue, 04 Nov 2014 17:24:13 GMT
            [4] => Server: nginx
            [5] => Vary: Accept-Encoding
            [6] => Content-Length: 1843
            [7] => Connection: keep-alive
        )

    [response_json:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [status_code] => 200
            [response_size] => 1843
            [data] => Array
                (
                    [0] => Array
                        (
                            [account_id] => 1587
                            [id] => 180684
                            [cell_phone] => 
                            [created] => 2014-10-31 17:42:17
                            [email] => apiAutoTesterOkFf@tune.com
                            [first_name] => apiAutoTester
                            [google_auth_enabled] => 0
                            [google_auth_verified] => 0
                            [last_name] => 
                            [modified] => 2014-10-31 17:42:17
                            [name] => apiAutoTester
                            [phone] => 
                            [signup_ip] => 
                            [status] => active
                            [time_zone] => America/Los_Angeles
                            [title] => 
                        )

==========================================================
 Account Users CSV report for export.             
==========================================================
= TuneManagementResponse:
Tune\Management\Shared\Service\TuneManagementResponse Object
(
    [request_url:Tune\Management\Shared\Service\TuneManagementResponse:private] => https://api.mobileapptracking.com/v2/account/users/find_export_queue?api_key=API_KEY&fields=account_id%2Ccell_phone%2Ccreated
%2Cemail%2Cfirst_name%2Cgoogle_auth_enabled%2Cgoogle_auth_verified%2Cid%2Clast_name%2Cmodified%2Cname%2Cpassword%2Cphone%2Csignup_ip%2Cstatus%2Ctime_zone%2Ctitle&format=csv
    [response_http_code:Tune\Management\Shared\Service\TuneManagementResponse:private] => 200
    [response_headers:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [0] => HTTP/1.1 200 OK
            [1] => Access-Control-Allow-Origin: *
            [2] => Content-Type: application/json
            [3] => Date: Tue, 04 Nov 2014 17:26:53 GMT
            [4] => Server: nginx
            [5] => Vary: Accept-Encoding
            [6] => Content-Length: 82
            [7] => Connection: keep-alive
        )

    [response_json:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [status_code] => 200
            [response_size] => 83
            [data] => 204216b8809a35da62d259620db1ce1b
        )

)

=======================================================
 Fetching Account Users CSV report.                    
=======================================================
Starting worker...
= attempt: 1, response: Tune\Management\Shared\Service\TuneManagementResponse Object
(
    [request_url:Tune\Management\Shared\Service\TuneManagementResponse:private] => https://api.mobileapptracking.com/v2/export/download?api_key=API_KEY&job_id=204216b8809a35da62d259620db1ce1b
    [response_http_code:Tune\Management\Shared\Service\TuneManagementResponse:private] => 200
    [response_headers:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [0] => HTTP/1.1 200 OK
            [1] => Access-Control-Allow-Origin: *
            [2] => Content-Type: application/json
            [3] => Date: Tue, 04 Nov 2014 17:26:54 GMT
            [4] => Server: nginx
            [5] => Vary: Accept-Encoding
            [6] => Content-Length: 102
            [7] => Connection: keep-alive
        )

    [response_json:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [status_code] => 200
            [response_size] => 102
            [data] => Array
                (
                    [status] => pending
                    [percent_complete] => 0
                    [data] => 
                )

        )

)

= attempt: 1, response: Tune\Management\Shared\Service\TuneManagementResponse Object
(
    [request_url:Tune\Management\Shared\Service\TuneManagementResponse:private] => https://api.mobileapptracking.com/v2/export/download?api_key=API_KEY&job_id=204216b8809a35da62d259620db1ce1b
    [response_http_code:Tune\Management\Shared\Service\TuneManagementResponse:private] => 200
    [response_headers:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [0] => HTTP/1.1 200 OK
            [1] => Access-Control-Allow-Origin: *
            [2] => Content-Type: application/json
            [3] => Date: Tue, 04 Nov 2014 17:27:04 GMT
            [4] => Server: nginx
            [5] => Vary: Accept-Encoding
            [6] => Content-Length: 400
            [7] => Connection: keep-alive
        )

    [response_json:Tune\Management\Shared\Service\TuneManagementResponse:private] => Array
        (
            [status_code] => 200
            [response_size] => 400
            [data] => Array
                (
                    [status] => complete
                    [percent_complete] => 100
                    [data] => Array
                        (
                            [format] => csv
                            [url] => https://s3.amazonaws.com/hasfiles/tmp/1415122014-3600-54590c5ec9680.csv?response-content-disposition=attachment%3B%20filename%3D%22tmp%2F1415122014-3600-54590c5ec9680.csv%22&AWSAccessKeyId=AKIAJLQ
46HMMMRGFKZHQ&Expires=1415125615&Signature=0ZXUkXzd5JSlPcFvGt9B0XXugRk%3D
                        )

                )

        )

)


Completed worker...
= CSV Report URL: https://s3.amazonaws.com/hasfiles/tmp/1415122014-3600-54590c5ec9680.csv?response-content-disposition=attachment%3B%20filename%3D%22tmp%2F1415122014-3600-54590c5ec9680.csv%22&AWSAccessKeyId=AKIAJLQ46HMMMRGFKZHQ&Expir
es=1415125615&Signature=0ZXUkXzd5JSlPcFvGt9B0XXugRk%3D
=============================================================
 Read Account Users CSV report and pretty print 5 lines.     
=============================================================
Report REPORT_URL: https://s3.amazonaws.com/hasfiles/tmp/1415122014-3600-54590c5ec9680.csv?response-content-disposition=attachment%3B%20filename%3D%22tmp%2F1415122014-3600-54590c5ec9680.csv%22&AWSAccessKeyId=AKIAJLQ46HMMMRGFKZHQ&Expi
res=1415125615&Signature=0ZXUkXzd5JSlPcFvGt9B0XXugRk%3D
Report total row count: 33
Report data size: 1
------------------
1. 'account_id','cell_phone','created','email','first_name','google_auth_enabled','google_auth_verified','id','last_name','modified','name','password','phone','signup_ip','status','time_zone','title'
2. '1587','','2011-11-21 03:15:21','demo@hasoffers.com','Demo','0','0','3013','Person','2014-06-09 09:24:36','Demo Person','NULL','999-999-9999','','active','America/Los_Angeles',''
3. '1587','','2012-04-16 13:46:07','playheaven@hasoffers.com','Playhaven','0','0','3779','Playhaven','2012-04-16 13:46:07','Playhaven Playhaven','NULL','2065081318','','active','America/Chicago','API test'
4. '1587','','2012-07-22 17:28:01','demo@mobileapptracking.com','Jane','0','0','5032','Does','2012-07-22 17:28:01','Jane Does','NULL','206-508-1318','','active','America/Los_Angeles','Demo'
5. '1587','512-925-0000','2012-11-26 12:58:07','info+1@hasoffers.com','SXSW Awards','0','0','7268','Judges','2012-11-26 12:58:48','SXSW Awards Judges','NULL','512-925-0000','','active','America/Chicago',''
------------------
```

























