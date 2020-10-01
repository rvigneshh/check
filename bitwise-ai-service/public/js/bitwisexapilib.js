jQuery(document).ready(function() {

var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

// Listen to message from child window
eventer(messageEvent,function(e) {
        if(typeof e.data['data'] !== "undefined")
        {
                XapiStatement(e.data['fullUrl'],e.data['headers'],e.data['method'],e.data['data']);
        }
},false);

});

//Customized XAPI send function
var bitwiseLib = true;

function XapiStatement(fullUrl,headers,method,data)
{

var refurl = fullUrl.split('?')[0];
var stype = refurl.substring(refurl.lastIndexOf('/') + 1);

//Processing based on XAPI Type
if (stype == 'statements') {

	var requrl = new URL(fullUrl);
	var stmtid = requrl.searchParams.get("statementId");
	if (!stmtid) {
		var stmtid = JSON.parse(data).id;
	}

	//Checking Indexed DB Enabled
	if(parent.indexdb == 'Yes')
	{

		parent.localforage.getItem('bW_statements').then(function(value) {
		if(value == null)
		{
			//If Key Value Does not Exists/Empty
			parent.localforage.setItem('bW_statements', [{
			"requesturl": decodeURI(fullUrl),
			"requestheader": headers,
			"requestmethod": method,
			"requestdata": JSON.parse(data),
			"stmtid": stmtid
			}])
		}
		else
		{
			//If Key Value Already Exists
			parent.localforage.getItem('bW_statements', function(err, value){
			//Adding new data to the key
			value.push({
			"requesturl": decodeURI(fullUrl),
			"requestheader": headers,
			"requestmethod": method,
			"requestdata": JSON.parse(data),
			"stmtid": stmtid
			});
			parent.localforage.setItem('bW_statements', value );
			});
		}
		}).catch(function(err) {
			console.log(err);
		});

	}
	//If Indexed DB not Enabled. Writing directly to RabbitMQ
	else
	{

		var stmt = {"requesturl": decodeURI(fullUrl),"requestheader": headers,"requestmethod": method,"requestdata": JSON.parse(data),"stmtid": stmtid};

		//Sending Statements to RabbitMQ Directly
		jQuery.post(parent.xapiAjax.ajaxurl, { action: "bit_xapi_stmt",stmt: stmt }, function(data) {});

	}

}
else if(stype == 'state') {

	var requrl = new URL(fullUrl);
    	var state = requrl.searchParams.get("stateId");
    	var activityid = requrl.searchParams.get("activityId");
    	var activity = activityid.replace('http://', '').replace('https://', '').split(/[/?#]/)[0];
    	var ref = (decodeURI(activity));

	//If state is Cumulative Time
	if(state == 'cumulative_time')
	{
		//Checking Indexed DB
		if(parent.indexdb == 'Yes')
		{
			parent.localforage.getItem('bW_ctime').then(function(value) {
        		if(value == null)
        		{
	                        //If Key Value Does not Exists/Empty
        			parent.localforage.setItem('bW_ctime', [{
        			"requesturl": decodeURI(fullUrl),
        			"requestheader": headers,
       				"requestmethod": method,
        			"requestdata": decodeURI(data),
        			"ref": ref
        			}])
			}
			else
			{
	                        //If Key Value Already Exists
				parent.localforage.getItem('bW_ctime', function(err, value){
                                //Adding new data to the key
        			value.push({
        			"requesturl": decodeURI(fullUrl),
        			"requestheader": headers,
        			"requestmethod": method,
        			"requestdata": decodeURI(data),
        			"ref": ref
        			});
        			parent.localforage.setItem('bW_ctime', value );
        			});
        		}
        		}).catch(function(err) {
        			console.log(err);
        		});
		}
	        //If Indexed DB not Enabled. Writing directly to RabbitMQ
		else
		{

	                var stmt = {"requesturl": decodeURI(fullUrl),"requestheader": headers,"requestmethod": method,"requestdata": JSON.parse(data),"ref": ref};

        	        //Sending Statements to RabbitMQ Directly
                	jQuery.post(parent.xapiAjax.ajaxurl, { action: "bit_xapi_stmt",stmt: stmt }, function(data) {});

		}
	}
        //If state is Suspend Data
	else if(state == 'suspend_data' && method !== 'GET')
	{
		//Checking Indexed DB
                if(parent.indexdb == 'Yes')
                {
                        parent.localforage.getItem('bW_sdata').then(function(value) {

				if(value == null)
				{
					//If Key Value does not exist 
                                	parent.localforage.setItem('bW_sdata', [{
                                	"requesturl": decodeURI(fullUrl),
                                	"requestheader": headers,
                                	"requestmethod": method,
                                	"requestdata": decodeURI(data),
                                	"ref": ref
                                	}])
				}
				else
				{
					var found_sdata = value.some(el => el.ref === ref && el.requestmethod === method);
                            		if (!found_sdata) {
        					value.push({
        					"requesturl": decodeURI(fullUrl),
        					"requestheader": headers,
        					"requestmethod": method,
        					"requestdata": decodeURI(data),
        					"ref": ref
        					})
        					parent.localforage.setItem('bW_ctime', value );
        				}
				}


                        }).catch(function(err) {
                                    console.log(err);
                        });
                }
                //If Indexed DB not Enabled. Writing directly to RabbitMQ
                else
                {

                        var stmt = {"requesturl": decodeURI(fullUrl),"requestheader": headers,"requestmethod": method,"requestdata": JSON.parse(data),"ref": ref};

                        //Sending Statements to RabbitMQ Directly
                        jQuery.post(parent.xapiAjax.ajaxurl, { action: "bit_xapi_stmt",stmt: stmt }, function(data) {});

                }

	}
        //If state is Bookmark
	else if(state == 'bookmark' && method !== 'GET')
	{
		//Checking Indexed DB
                if(parent.indexdb == 'Yes')
                {
                        parent.localforage.getItem('bW_bmark').then(function(value) {

                                //If Key Value Does not Exists/Empty
                                parent.localforage.setItem('bW_bmark', [{
                                "requesturl": decodeURI(fullUrl),
                                "requestheader": headers,
                                "requestmethod": method,
                                "requestdata": decodeURI(data),
                                "ref": ref
                                }])

                        }).catch(function(err) {
                                    console.log(err);
                        });
                }
                //If Indexed DB not Enabled. Writing directly to RabbitMQ
                else
                {

                        var stmt = {"requesturl": decodeURI(fullUrl),"requestheader": headers,"requestmethod": method,"requestdata": JSON.parse(data),"ref": ref};

                        //Sending Statements to RabbitMQ Directly
                        jQuery.post(parent.xapiAjax.ajaxurl, { action: "bit_xapi_stmt",stmt: stmt }, function(data) {});

                }

	}

}

}
