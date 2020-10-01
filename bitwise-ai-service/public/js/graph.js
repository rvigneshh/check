(function( $ ) {
	'use strict';
        console.log("graph.js");

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

  		//console.dir(php_vars.datum);

  		if(php_vars.type == 'bar_chart') {

  			nv_bar_chart(php_vars);

  		}

  		if(php_vars.type == 'pie_chart') {

		    var testdata2 = php_vars.datum;

		    var height = 350;
		    var width = 350;

		    nv.addGraph(function() {
		        var chart = nv.models.pieChart()
		            .x(function(d) { return d.key })
		            .y(function(d) { return d.value })
		            .width(width)
		            .height(height);

		        d3.select("#test1")
		            .datum(testdata2)
		            .transition().duration(1200)
		            .attr('width', width)
		            .attr('height', height)
		            .call(chart);

		        return chart;
		    });
		}

		function formatoHHMMSS(secs){
		    var hours = parseInt( secs / 3600 ) % 24;
		    var minutes = parseInt( secs / 60 ) % 60;
		    var seconds = secs % 60;
		    return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
		};

		function nv_bar_chart(php_vars){
			
			nv.addGraph(function() {

  				var forceY_val = parseInt(php_vars.forceY.forceY);
	  		    var chart = nv.models.discreteBarChart()
	  		        .x(function(d) { return d.label })
	  		        .y(function(d) { return d.value })
	  		        .staggerLabels(true)
	  		        //.staggerLabels(historicalBarChart[0].values.length > 8)
	  		        .showValues(true) //Show bar value next to each bar.
	  		        .duration(250)
	  		        .color(['#2196F3'])
	  		        .margin({top: 50, right: 30, bottom: 90, left: 80})
	  		        .valueFormat(function(d) { return formatoHHMMSS(d)});
				
				chart.xAxis.axisLabel('By Month');
				chart.yAxis.axisLabel('Time Spent (HH:MM:SS)');
				chart.yAxis.margin({top: 5, right: 0, bottom: 50, left: 90});	

/*				chart.lines.yScale(d3.time.scale());
				chart.yAxis.showMaxMin(false)
				          .tickFormat(function(d) {
				              return d3.time.format('%H:%M:%S')(new Date(d))
				          });
				      ;*/

				
				// chart.yAxis.ticks(d3.time.minutes, 15)
				// chart.yAxis.tickFormat(function(d) {
				//               return d3.time.format('%H:%M:%S')(new Date(2017, 2, 2, 0, d))
				//            });
				
				// chart.ticks(); 
				// chart.yAxis.tickFormat(function(d) { return formatoHHMMSS(d)});

				// console.log(forceY_val*1000);
				chart.forceY( [0, forceY_val] ); 
				// chart.yAxis.ticks( [0, forceY_val*1000)] ); 
				chart.yAxis.tickFormat(function(d) { return formatoHHMMSS(d)});

				// chart.yAxis.tickFormat(function(d) { return d3.time.format('%H:%M:%S')(new Date(d))});
				
	  		    var svg = d3.select('#chart1 svg')
	  		        .datum(php_vars.datum)
	  		        .call(chart);	

	  		    nv.utils.windowResize(chart.update);
	  		    return chart;

  			});
  		}

  		//console.log(node_vars);

  		var root = node_vars;
  		//console.log(node_vars);
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
          // .attr("height", h + m[0] + m[4])
          .attr("height", 450)
  		    .attr("align","center")
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
  		toggle(root.children[9]);
  		toggle(root.children[9].children[0]);

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

	});

})( jQuery );	
