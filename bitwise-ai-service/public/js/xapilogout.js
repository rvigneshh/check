jQuery(document).ready(function() {

console.log("xapilogout.js");

jQuery(".logout a").click(function(event) {

	if(window.indexedDB)
	{
  		event.preventDefault();
  		var href = $(this).attr('href');
	}

	var keys = ['bW_statements','bW_ctime','bW_sdata','bW_bmark'];
	parent.localforage.getItems(keys).then(function(results) {
	//Check If Statement Exists
	var st = results.bW_statements;
	var ct = results.bW_ctime;
	var sd = results.bW_sdata;
	var bm = results.bW_bmark;

	if(typeof st == "undefined") { var st = []; }
	if(typeof ct == "undefined") { var ct = []; }
	if(typeof sd == "undefined") { var sd = []; }
	if(typeof bm == "undefined") { var bm = []; }
	var stm = st.concat(ct,sd,bm);

	if(stm.length >= 1)
	{
		/*Modified By Vignesh R*/
		jQuery.post(xapiAjax.ajaxurl, { action: "bit_xapi_stmt",stmt: JSON.stringify(stm) }, function(data) {
			if(data == 'success')
			{
				//Removing after getting success Response
				parent.localforage.removeItem('bW_statements');
				parent.localforage.removeItem('bW_ctime');
				parent.localforage.removeItem('bW_bmark');
				parent.localforage.removeItem('bW_sdata');
				window.location = href;
			}
			else
			{
				window.location = href;
			}
		},"json");
	}
	else
	{
		window.location = href;
	}
	});

});

//After logout
var url = window.location.pathname;
var refurl = url.split('/')[1];

if(refurl == 'login')
{
        var keys = ['bW_statements','bW_ctime','bW_sdata','bW_bmark'];
        parent.localforage.getItems(keys).then(function(results) {
        //Check If Statement Exists
        var st = results.bW_statements;
        var ct = results.bW_ctime;
        var sd = results.bW_sdata;
        var bm = results.bW_bmark;

        if(typeof st == "undefined") { var st = []; }
        if(typeof ct == "undefined") { var ct = []; }
        if(typeof sd == "undefined") { var sd = []; }
        if(typeof bm == "undefined") { var bm = []; }
        var stm = st.concat(ct,sd,bm);

        if(stm.length >= 1)
        {
		/*Modified By Vignesh R*/
                jQuery.post(myAjax.ajaxurl, { action: "bit_xapi_stmt",stmt: JSON.stringify(stm) }, function(data) {
                        if(data == 'success')
                        {
                                //Removing after getting success Response
                                parent.localforage.removeItem('bW_statements');
                                parent.localforage.removeItem('bW_ctime');
                                parent.localforage.removeItem('bW_bmark');
                                parent.localforage.removeItem('bW_sdata');
                        }
                },"json");
        }
        });

}

});
