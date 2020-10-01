	(function( $ ) {
	'use strict';
	console.log("bitwise-data-visualisation-public.js");
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	$( window ).load(function() {

	// $(function(){
		$('#searchStudent').change(function(){
			
			var val = $(this).val();
			var student_id = $('#student_id').val();
			$.ajax({
			        
			        type : "post",

			        dataType : "json",

			        url : myAjax.ajaxurl,

			        data : {action: "search_course", val:val, student_id:student_id},

			        success:function(response) {

			        	if(response.forceY === null){

			  				$(".chart_area").html("<div style='width:100%;height:500px; text-align:center; padding-top:50px; font-size:20px; class='text-center'><strong>No Data Found</strong></div>");
			  			}else{
			  				// console.dir(console);
			  				$('.chat_area').html('');
			  				$(".chart_area").html("<span class='chart_area'><div style='width:100%;height:500px;' id='chart1'><svg></svg></div></span>");
			  				nv_bar_chart(response);
			  			}
			         	
			        }
			   	});
		});


		$('#kcg-search').change(function(){
			var val = $(this).val();
			var student_id = $('#kcg-student_id').val();

			$.ajax({
			        
			        type : "post",

			        dataType : "json",

			        url : myAjax.ajaxurl,

			        data : {action: "kcg_search_course", val:val, student_id:student_id},

			        success:function(response) {

			  			//console.dir(response);
			        	if(response == null){
			  				$('.kcg_chart_area').html('');
			  				$(".kcg_chart_area").html("<div style='width:100%;height:500px; text-align:center; padding-top:50px; font-size:20px; class='text-center'><strong>No Data Found</strong></div>");
			  			}else{
			  				$('.kcg_chart_area').html('');
			  				$(".kcg_chart_area").html('<div id="kcg-chart"></div>');
			  				kcg_chart(response);
			  			}
			         	
			        }
			   	});
		});

		/*Added By Vignesh R on Aug 07th 2020*/
                $('#iq-mail').click(function(){
                        var userid = $(this).data('uid');
                        $.ajax({
                                type : "post",
                                url : myAjax.ajaxurl,
                                data : {action: "bit_iq_mail", userid:userid},
                                success:function(data) {
					swal({ html:true, title: '', text:'<p style="text-align: center; font-family: Lato; font-size: 1.25rem; color: #000; font-weight: 900">'+data+'</p>'});
          			}
                         });
                });

		/*Added By Vignesh R on Aug 07th 2020*/		
		$('#iqkcg-search').change(function(){
			var val = $(this).val();
			var student_id = $('#iqkcg-student_id').val();

			$.ajax({
			        
			        type : "post",

			        dataType : "json",

			        url : myAjax.ajaxurl,

			        data : {action: "iq_kcg_search_course", val:val, student_id:student_id},

			        success:function(response) {

			  			//console.dir(response);
			        	if(response == null){
			  				$('.iqkcg_chart_area').html('');
			  				$(".iqkcg_chart_area").html("<div style='width:100%;height:500px; text-align:center; padding-top:50px; font-size:20px; class='text-center'><strong>No Data Found</strong></div>");
			  			}else{
			  				$('.iqkcg_chart_area').html('');
			  				$(".iqkcg_chart_area").html('<div id="iqkcg-chart"></div>');
			  				iqkcg_chart(response);
			  			}
			         	
			        }
			   	});
		});


		function iqkcg_chart(node_vars) {
  			
  			function wrap(text, width) {
			    text.each(function () {
			        var text = d3.select(this),
			            words = text.text().split(/\s+/).reverse(),
			            word,
			            line = [],
			            lineNumber = 0,
			            lineHeight = 1.4, // ems
			            x = text.attr("x"),
			            y = text.attr("y"),
			            dy = 0, //parseFloat(text.attr("dy")),
			            tspan = text.text(null)
			                        .append("tspan")
			                        .attr("x", x)
			                        .attr("y", y)
			                        .attr("dy", dy + "em");
			        while (word = words.pop()) {
			            line.push(word);
			            tspan.text(line.join(" "));
			            if (tspan.node().getComputedTextLength() > width) {
			                line.pop();
			                tspan.text(line.join(" "));
			                line = [word];
			                tspan = text.append("tspan")
			                            .attr("x", x)
			                            .attr("y", y)
			                            .attr("dy", ++lineNumber * lineHeight + dy + "em")
			                            .text(word);
			            }
			        }
			    });
			}

  		//console.log(node_vars);

					var root = node_vars;
					var m = [20, 320, 20, 120],
					    w = 300,
					    h = 450,
					    i = 0;
					    
					var tree = d3.layout.tree()
					    .size([h, w]);

					var diagonal = d3.svg.diagonal()
					    .projection(function(d) {
					        return [d.y, d.x];
					    });


					var vis = d3.select("#iqkcg-chart").append("svg:svg")
					    .attr("width", w + m[1] + m[3])
			        	// .attr("height", h + m[0] + m[2])
			        	.attr("height", 450)
					    .attr("align","center")
					    .attr("style","overflow-y: auto")
					    .append("svg:g")
					    .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

					root.x0 = h / 2;
					root.y0 = 0;

					function toggleAll(d) {
					    if (d.children) {
					        d.children.forEach(toggleAll);
					        toggle(d);
					    }
					}

					root.children.forEach(toggleAll);
					toggle(root.children[3]);
					toggle(root.children[3].children[0]);
					toggle(root.children[5]);
					toggle(root.children[5].children[0]);

					update(root);
				
					function update(source) {
					    var duration = d3.event && d3.event.altKey ? 5000 : 500;

					    // compute the new height
					    var levelWidth = [1];
					    var childCount = function(level, n) {

					        if (n.children && n.children.length > 0) {
					            if (levelWidth.length <= level + 1) levelWidth.push(0);

					            levelWidth[level + 1] += n.children.length;
					            n.children.forEach(function(d) {
					                childCount(level + 1, d);
					            });
					        }
					    };
					    childCount(0, root);
					    var newHeight = d3.max(levelWidth) * 35; // 20 pixels per line  
					    tree = tree.size([newHeight, w]);

					    // Compute the new tree layout.
					    var nodes = tree.nodes(root).reverse();

					    // Normalize for fixed-depth.
					    nodes.forEach(function(d) {
					        d.y = d.depth * 250;
					    });

					    // Update the nodes…
					    var node = vis.selectAll("g.node")
					        .data(nodes, function(d) {
					            return d.id || (d.id = ++i);
					        });

					    // Enter any new nodes at the parent's previous position.
					    var nodeEnter = node.enter().append("svg:g")
					        .attr("class", "node")
					        .attr("transform", function(d) {
					            return "translate(" + source.y0 + "," + source.x0 + ")";
					        })
					        .on("click", function(d) {
					            toggle(d);
					            update(d);
					        });

					    nodeEnter.append("svg:circle")
					        .attr("r", 1e-6)
					        .style("fill", function(d) {
					            if (d.cat == 0) return "grey";
					            if (d.cat == 1) return "red";
					            if (d.cat == 2) return "yellow";
					            if (d.cat == 3) return "green";
					            //return d._children ? "lightsteelblue" : "#fff";
					        })
					        // add tool tip for ps -eo pid,ppid,pcpu,size,comm,ruser,s
					        .on("mouseover", function(d) {
					            div.transition()
					                .duration(200)
					                .style("width", "170px")
                        			.style("background", "#0f4982")
                        			.style("color","white")
					                .style("opacity", .9);
					            div.html(
					                    "score: " + d.score + "<br/>" +
					                    "Category: " + (d.cat == 0 ? "Not taken yet" : d.cat == 1 ? "Not Satisfactory" : d.cat == 2 ? "Avg" : d.cat == 3 ? "Good" : "Error") + "<br/>"
					                )
					                .style("left", (d3.event.pageX) + "px")
					                .style("top", (d3.event.pageY - 28) + "px");
					        })
					        .on("mouseout", function(d) {
					            div.transition()
					                .duration(500)
					                .style("opacity", 0);
					        });

					    nodeEnter.append("svg:text")
					        .attr("x", function(d) {
					            return d.children || d._children ? -10 : 10;
					        })
					        .attr("dy", ".35em")
					        .attr("text-anchor", function(d) {
					            return d.children || d._children ? "end" : "start";
					        })
					        .text(function(d) {
					            return d.name;
					        })
					        .attr("class", "shadow")
					        .style("fill-opacity", 1e-6)
					        .call(wrap, 160);

					    // add the tool tip
					    var div = d3.select("body").append("div")
					        .attr("class", "tooltip")
					        .style("opacity", 0);

					    // Transition nodes to their new position.
					    var nodeUpdate = node.transition()
					        .duration(duration)
					        .attr("transform", function(d) {
					            return "translate(" + d.y + "," + d.x + ")";
					        });

					    nodeUpdate.select("circle")
					        .attr("r", 4)
					        .style("fill", function(d) {
					            if (d.cat == 0) return "#2e588d";
					            if (d.cat == 1) return "red";
					            if (d.cat == 2) return "yellow";
					            if (d.cat == 3) return "green";
					        });

					    nodeUpdate.select("text")
					        .style("fill-opacity", 1);

					    // Transition exiting nodes to the parent's new position.
					    var nodeExit = node.exit().transition()
					        .duration(duration)
					        .attr("transform", function(d) {
					            return "translate(" + source.y + "," + source.x + ")";
					        })
					        .remove();

					    nodeExit.select("circle")
					        .attr("r", 1e-6);

					    nodeExit.select("text")
					        .style("fill-opacity", 1e-6);

					    // Update the links…
					    var link = vis.selectAll("path.link")
					        .data(tree.links(nodes), function(d) {
					            return d.target.id;
					        });

					    // Enter any new links at the parent's previous position.
					    link.enter().insert("svg:path", "g")
					        .attr("class", "link")
					        .attr("d", function(d) {
					            var o = {
					                x: source.x0,
					                y: source.y0
					            };
					            return diagonal({
					                source: o,
					                target: o
					            });
					        })
					        .transition()
					        .duration(duration)
					        .attr("d", diagonal);

					    // Transition links to their new position.
					    link.transition()
					        .duration(duration)
					        .attr("d", diagonal);

					    // Transition exiting nodes to the parent's new position.
					    link.exit().transition()
					        .duration(duration)
					        .attr("d", function(d) {
					            var o = {
					                x: source.x,
					                y: source.y
					            };
					            return diagonal({
					                source: o,
					                target: o
					            });
					        })
					        .remove();

					    // Stash the old positions for transition.
					    nodes.forEach(function(d) {
					        d.x0 = d.x;
					        d.y0 = d.y;
					    });
					    window.setTimeout(function() {
					        var max = d3.max(d3.selectAll(".node")[0], function(g) {
					            return d3.transform(d3.select(g).attr("transform")).translate[1];
					        });
					        d3.select("svg").attr("height", max + 100)
					            //console.log(max)
					    }, 800)
					}
					
					function toggle(d) {
					    if (d.children) {
					        d._children = d.children;
					        d.children = null;
					    } else {
					        d.children = d._children;
					        d._children = null;
					    }
					}
		}



  		function nv_bar_chart(php_vars){
	
				nv.addGraph(function() {
					//console.dir(php_vars);
	  				var forceY_val = parseInt(php_vars.forceY.forceY);
		  		    var chart = nv.models.discreteBarChart()

		  		        .x(function(d) { return d.label })
		  		        .y(function(d) { return d.value })
		  		        .staggerLabels(true)
		  		        .showValues(true) //Show bar value next to each bar.
		  		        .duration(250)
		  		        .color(['#2196F3'])
		  		        .margin({top: 50, right: 30, bottom: 90, left: 80})
		  		        .valueFormat(function(d) { return formatoHHMMSS(d)});
					
					chart.xAxis.axisLabel('By Month');
					chart.yAxis.axisLabel('Time Spent (HH:MM:SS)');
					chart.yAxis.margin({top: 5, right: 0, bottom: 50, left: 90});	


					chart.forceY( [0, forceY_val] ); 

					chart.yAxis.tickFormat(function(d) { return formatoHHMMSS(d)});

					
		  		    var svg = d3.select('#chart1 svg')
		  		        .datum(php_vars.datum)
		  		        .call(chart);	

		  		    nv.utils.windowResize(chart.update);
		  		    return chart;

	  			});

	  			function formatoHHMMSS(secs){
				    var hours = parseInt( secs / 3600 ) % 24;
				    var minutes = parseInt( secs / 60 ) % 60;
				    var seconds = secs % 60;
				    return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
				};	
  		}	
		
		
  		function kcg_chart(node_vars) {
					var root = node_vars;
					var m = [20, 320, 20, 320],
					    w = 300,
					    h = 450,
					    i = 0;
					    
					var tree = d3.layout.tree()
					    .size([h, w]);

					var diagonal = d3.svg.diagonal()
					    .projection(function(d) {
					        return [d.y, d.x];
					    });


					var vis = d3.select("#kcg-chart").append("svg:svg")
					    .attr("width", w + m[1] + m[3])
			        	// .attr("height", h + m[0] + m[2])
			        	.attr("height", 450)
					    .attr("align","center")
					    .attr("style","overflow-y: auto")
					    .append("svg:g")
					    .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

					root.x0 = h / 2;
					root.y0 = 0;

					function toggleAll(d) {
					    if (d.children) {
					        d.children.forEach(toggleAll);
					        toggle(d);
					    }
					}

					root.children.forEach(toggleAll);
					toggle(root.children[1]);
					toggle(root.children[1].children[2]);
					toggle(root.children[3]);
					toggle(root.children[3].children[0]);

					update(root);
				
					function update(source) {
					    var duration = d3.event && d3.event.altKey ? 5000 : 500;

					    // compute the new height
					    var levelWidth = [1];
					    var childCount = function(level, n) {

					        if (n.children && n.children.length > 0) {
					            if (levelWidth.length <= level + 1) levelWidth.push(0);

					            levelWidth[level + 1] += n.children.length;
					            n.children.forEach(function(d) {
					                childCount(level + 1, d);
					            });
					        }
					    };
					    childCount(0, root);
					    var newHeight = d3.max(levelWidth) * 20; // 20 pixels per line  
					    tree = tree.size([newHeight, w]);

					    // Compute the new tree layout.
					    var nodes = tree.nodes(root).reverse();

					    // Normalize for fixed-depth.
					    nodes.forEach(function(d) {
					        d.y = d.depth * 180;
					    });

					    // Update the nodes…
					    var node = vis.selectAll("g.node")
					        .data(nodes, function(d) {
					            return d.id || (d.id = ++i);
					        });

					    // Enter any new nodes at the parent's previous position.
					    var nodeEnter = node.enter().append("svg:g")
					        .attr("class", "node")
					        .attr("transform", function(d) {
					            return "translate(" + source.y0 + "," + source.x0 + ")";
					        })
					        .on("click", function(d) {
					            toggle(d);
					            update(d);
					        });

					    nodeEnter.append("svg:circle")
					        .attr("r", 1e-6)
					        .style("fill", function(d) {
					            if (d.cat == 0) return "grey";
					            if (d.cat == 1) return "red";
					            if (d.cat == 2) return "yellow";
					            if (d.cat == 3) return "green";
					            //return d._children ? "lightsteelblue" : "#fff";
					        })
					        // add tool tip for ps -eo pid,ppid,pcpu,size,comm,ruser,s
					        .on("mouseover", function(d) {
					            div.transition()
					                .duration(200)
                                                        .style("width", "170px")
                                                        .style("background", "#0f4982")
                                                        .style("color","white")
					                .style("opacity", .9);
					            div.html(
					                    "Score: " + d.score + "<br/>" +
					                    "Category: " + (d.cat == 0 ? "Not taken yet" : d.cat == 1 ? "Not Satisfactory" : d.cat == 2 ? "Avg" : d.cat == 3 ? "Good" : "Error") + "<br/>"
					                )
					                .style("left", (d3.event.pageX) + "px")
					                .style("top", (d3.event.pageY - 28) + "px");
					        })
					        .on("mouseout", function(d) {
					            div.transition()
					                .duration(500)
					                .style("opacity", 0);
					        });

					    nodeEnter.append("svg:text")
					        .attr("x", function(d) {
					            return d.children || d._children ? -10 : 10;
					        })
					        .attr("dy", ".35em")
					        .attr("text-anchor", function(d) {
					            return d.children || d._children ? "end" : "start";
					        })
					        .text(function(d) {
					            return d.name;
					        })
					        .style("fill-opacity", 1e-6);

					    // add the tool tip
					    var div = d3.select("body").append("div")
					        .attr("class", "tooltip")
					        .style("opacity", 0);

					    // Transition nodes to their new position.
					    var nodeUpdate = node.transition()
					        .duration(duration)
					        .attr("transform", function(d) {
					            return "translate(" + d.y + "," + d.x + ")";
					        });

					    nodeUpdate.select("circle")
					        .attr("r", 5.2)
					        .style("fill", function(d) {
					            if (d.cat == 0) return "grey";
					            if (d.cat == 1) return "red";
					            if (d.cat == 2) return "yellow";
					            if (d.cat == 3) return "green";
					        });

					    nodeUpdate.select("text")
					        .style("fill-opacity", 1);

					    // Transition exiting nodes to the parent's new position.
					    var nodeExit = node.exit().transition()
					        .duration(duration)
					        .attr("transform", function(d) {
					            return "translate(" + source.y + "," + source.x + ")";
					        })
					        .remove();

					    nodeExit.select("circle")
					        .attr("r", 1e-6);

					    nodeExit.select("text")
					        .style("fill-opacity", 1e-6);

					    // Update the links…
					    var link = vis.selectAll("path.link")
					        .data(tree.links(nodes), function(d) {
					            return d.target.id;
					        });

					    // Enter any new links at the parent's previous position.
					    link.enter().insert("svg:path", "g")
					        .attr("class", "link")
					        .attr("d", function(d) {
					            var o = {
					                x: source.x0,
					                y: source.y0
					            };
					            return diagonal({
					                source: o,
					                target: o
					            });
					        })
					        .transition()
					        .duration(duration)
					        .attr("d", diagonal);

					    // Transition links to their new position.
					    link.transition()
					        .duration(duration)
					        .attr("d", diagonal);

					    // Transition exiting nodes to the parent's new position.
					    link.exit().transition()
					        .duration(duration)
					        .attr("d", function(d) {
					            var o = {
					                x: source.x,
					                y: source.y
					            };
					            return diagonal({
					                source: o,
					                target: o
					            });
					        })
					        .remove();

					    // Stash the old positions for transition.
					    nodes.forEach(function(d) {
					        d.x0 = d.x;
					        d.y0 = d.y;
					    });
					    window.setTimeout(function() {
					        var max = d3.max(d3.selectAll(".node")[0], function(g) {
					            return d3.transform(d3.select(g).attr("transform")).translate[1];
					        });
					        d3.select("svg").attr("height", max + 100)
					            //console.log(max)
					    }, 800)
					}
					
					function toggle(d) {
					    if (d.children) {
					        d._children = d.children;
					        d.children = null;
					    } else {
					        d.children = d._children;
					        d._children = null;
					    }
					}
		}



		$('#lb_search').change(function(){
			
			var val = $(this).val();
			console.log(val);
			$.ajax({
			        
			        type : "post",

			        dataType : "json",

			        url : myAjax.ajaxurl,

			        data : {action: "search_leaderboard_course", val:val},
			        // data : {action: "search_leaderboard_course", val:val, student_id:student_id},

			        success:function(response) {
			        	// var response = JSON.parse(response);
			        	// alert(response);
			         	console.log("response : " +response);

			         	if(response.mostTimeSpent.status == true){

			        		$("#lb-time-spent").html('');
			        		$("#lb-time-spent").html(response.mostTimeSpent.result);
			         	}

			         	if(response.mostTopicCompleted.status == true){
			         		$('#lb-topic-completed').html('');
			         		$("#lb-topic-completed").html(response.mostTopicCompleted.result);
			         	}

			         	if(response.mostQuesAnswered.status == true){
			         		$('#lb-ques-answered').html('');
			         		$("#lb-ques-answered").html(response.mostQuesAnswered.result);
			         	}

			         	if(response.mostBadgesEarned.status == true){
			         		$('#lb-badges-earned').html('');
			         		$("#lb-badges-earned").html(response.mostBadgesEarned.result);
			         	}


			         	// console.log(response.mostTimeSpent);
			        }
			   	});
		});	

	});
	
})( jQuery );
