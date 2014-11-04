
```url
https://api.mobileapptracking.com/v2/advertiser/stats/clicks/find_export_queue.json?start_date=2014-11-02+00:00:00&end_date=2014-11-03+23:59:59&fields=id,created,site_id,site.name,publisher_id,publisher.name,is_unique,advertiser_sub_campaign_id,advertiser_sub_campaign.ref,publisher_sub_campaign_id,publisher_sub_campaign.ref&format=csv&api_key=API_KEY&debug=0
```

```json
{
    status_code: 200,
    response_size: "83",
    data: "245ffc9a71ccb73a82ba97d26bc2010f"
}
```

https://api.mobileapptracking.com/v2/export/download.json?job_id=245ffc9a71ccb73a82ba97d26bc2010f&api_key=API_KEY&debug=0

```json
{  
   status_code:200,
   response_size:"400",
   data:{  
      status:"complete",
      percent_complete:100,
      data:{  
         format:"csv",
         url:"https://s3.amazonaws.com/hasfiles/tmp/1415123732-3600-5459131430350.csv?response-content-disposition=attachment%3B%20filename%3D%22tmp%2F1415123732-3600-5459131430350.csv%22&AWSAccessKeyId=AKIAJLQ46HMMMRGFKZHQ&Expires=1415127334&Signature=fXPVMe0ONs0kNS7g7t9cF7PTE5Y%3D"
      }
   }
}
```

```csv
id,created,site_id,site.name,publisher_id,publisher.name,is_unique,advertiser_sub_campaign_id,advertiser_sub_campaign.ref,publisher_sub_campaign_id,publisher_sub_campaign.ref
cd164d66720d0cc1e3-20141102-877,"2014-11-02 00:00:00",3626,"TESTING PINGDOM - DO NOT DELETE",0,organic,0,,,,
f8ffc7237f0aec9d88-20141102-877,"2014-11-02 00:00:00",533,"TEST UP TIME - DONT DELETE",1899,TestingPublisher,1,,,,
2c2a9d7e606b38a721-20141102-877,"2014-11-02 00:00:01",533,"TEST UP TIME - DONT DELETE",1899,TestingPublisher,1,,,,
455f4c79baacadf785-20141102-877,"2014-11-02 00:00:02",533,"TEST UP TIME - DONT DELETE",1899,TestingPublisher,1,,,,
33e3bad0f263e58091-20141102-877,"2014-11-02 00:00:05",533,"TEST UP TIME - DONT DELETE",365,"TESTING - DO NOT DELETE",1,,,,
d871e9133cf28d90ba-20141102-877,"2014-11-02 00:00:06",47266,"Testing Benchmarks",365,"TESTING - DO NOT DELETE",0,,,,
dff628d6c29252a4c4-20141102-877,"2014-11-02 00:00:06",47266,"Testing Benchmarks",365,"TESTING - DO NOT DELETE",0,,,,
```
