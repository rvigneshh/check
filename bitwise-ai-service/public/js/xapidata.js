console.log("xapidata.js");

var  browser = parent.browserName;
//Chrome Browser
if(browser == 'Chrome')
{
	async function detectMode()
	{
		if ('storage' in navigator && 'estimate' in navigator.storage)
		{
			const {usage, quota} = await navigator.storage.estimate();
			if(quota < 120000000){
				var inv = 60000;
				//console.log('Incognito')
			} else {
				var inv = 180000;
				//console.log('Not Incognito')
			}
		}
		else
		{
			//console.log('Can not detect')
		}
		setInterval(function(){

			//Fetching Value From Indexed DB
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
					}
					},"json");
				}
			});

		}, inv)
	}
	detectMode();
}
//Other Browser
else
{
	function detectPrivateMode(cb) {
		var db,
		on = cb.bind(null, true),
		off = cb.bind(null, false)

		//Safari
		function tryls() {
			try {
				localStorage.length ? off() : (localStorage.x = 1, localStorage.removeItem("x"), off());
			} catch (e) {
				// Safari only enables cookie in private mode
				navigator.cookieEnabled ? on() : off();
			}
		}

		// Chrome & Opera
		window.webkitRequestFileSystem ? webkitRequestFileSystem(0, 0, off, on)
		// FF
		: "MozAppearance" in document.documentElement.style ? (db = indexedDB.open("test"), db.onerror = on, db.onsuccess = off)
		// Safari
		: /constructor/i.test(window.HTMLElement) || window.safari ? tryls()
		// IE10+ & edge
		: !window.indexedDB && (window.PointerEvent || window.MSPointerEvent) ? on()
		// Rest
		: off()
	}

	detectPrivateMode(function (isPrivateMode) {
		if(isPrivateMode == true)
		{
			//Private Window
			var inv = 60000;
			//console.log('Private')
		}
		else
		{
			//Normal Window
			var inv = 180000;
			//console.log('Normal')
		}
		setInterval(function(){

			//Fetching Value From Indexed DB
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
					/*Modified by Vignesh R*/
                                        jQuery.post(xapiAjax.ajaxurl, { action: "bit_xapi_stmt",stmt: JSON.stringify(stm) }, function(data) {
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

		}, inv)
	})
}

