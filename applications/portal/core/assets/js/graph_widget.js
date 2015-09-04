/**
 * Graph Widget
 *
 * @Create by Dima Kudravcev (dmitrij@kudriavcev.info)
 * @Version 3.0 - 2015-06-01
 */


/**
 * Extens d3.selection prototipe with moveToFront function
 */
d3.selection.prototype.moveToFront = function() {
    return this.each(function() {
        this.parentNode.appendChild(this);
    });
};

/**
 * Add graph property to d3
 */
d3.graph = function() {
    /**
     * protected variables
     */

    var vis = null,
	svg = null,
	zoomContainer = null,
	graphCountainer = null,
	point = null,
	root = null,
	node = null,
	target = null,
//	slider = null,
	width = getWidth,
	height = getHeight,
	force = null,
	zoom = null,
	drag = null,
	gnodes = null,
	glinks = null,
	nodes = null, 
	links = null
	charge = -0.5e3,
	linkDistance = 50,
	radius = 16,
	legend = new Set(),
	scale = 1,
	imgPath = "/applications/apps/graph_widget/assets/images/";
	

    /**
      * object constructor
      */	
    function graph(container) {
	vis = container

	var w = width(), 
	    h = height()

	force = d3.layout.force() 
            .size([w, h])
            .charge(charge) 
//            .gravity(0)
            .linkDistance(linkDistance)

	drag = force.drag()
            .on("dragstart", dragstart)
            .on("drag", draged)	/*
            .on("dragend", function(d) {
		if (d.x < 16 || d.x > w-16 || d.y < 16 || d.y > h-16)
		    d.fixed = false;
	    })*/;
	    
/*	drag = d3.behavior.drag()
	    .origin(function(d) { return d; })
	    .on("dragstart", dragstart)
	    .on("drag", draged)
	//    .on("dragend", dragended);
*/	                    

	zoom = d3.behavior.zoom()
	    .scaleExtent([1, 2])
	    .on("zoom", zoomed)

	svg = vis.append("svg")
	    .attr("width", w)
	    .attr("height", h)

	point = svg.node().createSVGPoint()
	node = initNode();
	document.body.appendChild(node)

	zoomContainer = svg.append("g")
	    .call(zoom)
	    .on("dblclick.zoom", null)
	    
	var rect = zoomContainer.append("rect")
	    .attr("width", w)
	    .attr("height", h)
	    .style("fill", "none")
	    .style("pointer-events", "all");

	graphContainer = zoomContainer.append("g")

/*        var slider = vis.append("p").append("input")
	  .datum({})
	  .attr("type", "range")
	  .attr("value", zoom.scaleExtent()[0])
	  .attr("min", zoom.scaleExtent()[0])
	  .attr("max", zoom.scaleExtent()[1])
	  .attr("step", (zoom.scaleExtent()[1] - zoom.scaleExtent()[0]) / 100)
	  .on("input", slided);
*/
/*	svg.append("defs")
	    .selectAll("marker")
    	    .data(["suit"]).enter()
	    	.append("marker")
	    	    .attr("id", function(d) { return d; })
	    	    .attr("viewBox", "0 -5 10 10")
	    	    .attr("refX", 25)
	    	    .attr("refY", 0)
	    	    .attr("markerWidth", 5)
	    	    .attr("markerHeight", 5)
	    	    .attr("orient", "auto")
	    	.append("path")
	    	    .attr("d", "M0,-5L10,0L0,5Z")
		    .style("fill", "gray")
	    	    .style("stroke", "gray");
//	    	    .style("opacity", "0.6");*/

    }

    /**
     * Public methods
     */

   /**
     * Return or set width of the svg element
     */
    graph.width = function(v) {
	if (!arguments.length) return width
	width = v == null ? getWidth : d3.functor(v)

	return graph;
    };
    
   /**
     * Return or set height of the svg element
     */
    graph.height = function(v) {
	if (!arguments.length) return height
	height = v == null ? getHeight : d3.functor(v)

	return graph;
    };

    /**
     * Return or set height of the svg element
     */
    graph.imagePath = function(v) {
	if (!arguments.length) return imgPath
	imgPath = v

	return graph
    };

    /*
     * load JSON into widget
     */
    graph.load = function(json) {
	var map = {}, from, to, w = width(), h = height();

	// reset graph array	
	root = null;
    	nodes = json.nodes;
    	links = json.relationships;	
    	
//    	alert("nodes: " + nodes);
//    	alert("links: " + links);

    	// collect all nodes
    	for (var i=0;i<nodes.length;++i) {
	    var node = json.nodes[i];
	    node.collapsed = false;
	    node.hidden = false;
	    node.more = false;
	    node.selected = false;
	    node.links = {};
	    node.color = getColor(node);
	    node.stroke = getStrokeColor(node);

   	    if (indexOf(node.extras, 'root') !== -1)
                root = node;    

	    map[node.id] = node;
    	} 

        // set the root node to the graph centre
        if (null != root) {
/*            root.fixed = true;
	    root.x = w/2;
	    root.y = h/2;*/
	    root.color = "red";
        } 

        // alert(root);

    	circularLayout(nodes, { x: w/2, y: h/2 }, nodes.length * linkDistance / (2 * Math.PI));

    	// attach all relationships	
    	for (var i=0;i<links.length;++i) {
	    var link = links[i];

	    from = map[link.from];
	    to = map[link.to];

	    link.source=from;
	    link.target=to;

	    from.links[to.id] = to;
	    to.links[from.id] = from;
    	}

	return graph;
    };

    graph.update = function() {
	var _nodes = [], _links = [];
	
	if (null != root)
	    addLegend(0, "Current " + getName(root), root.color, root.stroke, getImage(root), getLabel(root));

        for (var i=0;i<nodes.length;++i)
	    if (!nodes[i].hidden) {
	        _nodes.push(nodes[i]);

		if (root != nodes[i] && !legend.has(nodes[i].type)) {
		    addLegend(legend.size + 1, getName(nodes[i]), nodes[i].color, nodes[i].stroke, getImage(nodes[i]), getLabel(nodes[i]));
		    legend.add(nodes[i].type);
                } 
            }

        for (var i=0;i<links.length;++i) 
	    if (!links[i].source.hidden && !links[i].target.hidden)
	        _links.push(links[i]);

        force
           .nodes(_nodes)
           .links(_links)
           .start();

        // Update the links…
        glinks = graphContainer.selectAll("line.graph-link")
            .data(_links);

    	// Enter any new links.
	glinks.enter().append("line")
            .attr("class", "graph-link")
	//    .style("marker-end",  "url(#suit)")
            .attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        // Exit any old links.
        glinks.exit().remove();


       // Update the nodes…
        gnodes = graphContainer.selectAll("g.graph-node")
            .data(_nodes, function(d) { return d.id; });
  
        var newNodes = gnodes.enter().append("g")
            .attr("class", "graph-node")
            .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; }) 
            .call(drag)
   	    .on('mouseover', showProperties)
            .on('mouseout', hideProperties)
            .on("dblclick", openUrl)
    
        newNodes.append("circle")
	    .attr("cx", 0)
	    .attr("cy", 0);

       if (null != imgPath)
	    newNodes.append("image")
		.attr("xlink:href", getImage)
		.attr("x", -radius/2)
		.attr("y", -radius/2)
		.attr("width", radius + "px")
		.attr("height", radius + "px"); 	
	else
	    newNodes.append("text")
     		.attr("text-anchor", "middle")
     		.attr("dominant-baseline", "middle")
		.text(getLabel);
              
        // Exit any old nodes.
        gnodes.exit().remove();

        gnodes.selectAll("circle")
	    .attr("r", getRadius)
	    .attr("fill", function(d) { return d.color; })
	    .attr("stroke", function(d) { return d.stroke; });

        force.on("tick", tick);

	return graph;
    };

    /**
     * Private methods
     */

    function showProperties() {
	var target, args = Array.prototype.slice.call(arguments);
    	if(args[args.length - 1] instanceof SVGElement) 
	    target = args.pop();
	else
	    target = d3.event.target;
    
	args[0].selected = true;
    
	d3.select(this).select('circle').attr("r", getRadius);

	var content = getPropertiesHtml.apply(this, args),
	    nodel = d3.select(node);

	nodel.html(content);

   	var matrix = target.getScreenCTM(),
	    tbbox = target.getBBox(),
	    scrollTop  = document.documentElement.scrollTop || document.body.scrollTop,
            scrollLeft = document.documentElement.scrollLeft || document.body.scrollLeft

	point.x = tbbox.x - node.offsetWidth / scale;
        point.y = tbbox.y - node.offsetHeight / (2 * scale);

	var pt = point.matrixTransform(matrix),
	    top = pt.y + scrollTop,
	    left = pt.x + scrollLeft;

        nodel.style({ opacity: 1, 'pointer-events': 'all', top: top + 'px', left: left + 'px'});
    }

    function hideProperties() {
	var args = Array.prototype.slice.call(arguments);

	args[0].selected = false;

	d3.select(this).select('circle').attr("r", getRadius);
        d3.select(node).style({ opacity: 0, 'pointer-events': 'none' })
    }

    function collapse(d) {
	if (d3.event.defaultPrevented) return;

	d.collapsed = !d.collapsed;
//	alert(d.collapsed);
	d.more = false; 
	
	if (d.collapsed) {
	    var n, n1, n2;
	    for (n1 in d.links) 
	    	d.links[n1].hidden = true;
	    
	    for (n1 in d.links) {
	    	n = d.links[n1];
	        for (n2 in n.links) {
		    if (!n.links[n2].hidden && !n.links[n2].collapsed) {
		        n.hidden = false;
		        break;
                    }			
                } 
            }

	    for (n1 in d.links) {
	    	n = d.links[n1];
		if (n.hidden) {
		    for (n2 in n.links) {
			if (!n.links[n2].hidden && !n.links[n2].collapsed) {
			    n.hidden = false;
			    break;
		        }			
		    } 
	
		    if (n.hidden && !d.more)
			d.more = true;
		}	    		
            } 
        } else 
	    for (n1 in d.links) 
	    	d.links[n1].hidden = false;
        
    //    alert("more:" +d.more);

	graph.update();
        gnodes.moveToFront();
    }

    /*
     * return width of attached svg object
     */ 	
    function getWidth() { return parseInt(vis.style("width")); }	

    /* 
     * return height of attached svg object
     */
    function getHeight() { return parseInt(vis.style("height")); }	


    /**
     * extracts SVG Node from the given element
     *   
     * @param el Node element
     */
    /*function getSVGNode(el) {
        el = el.node();
        if(el.tagName.toLowerCase() == 'svg')
            return el;

        return el.ownerSVGElement;
    }*/

    /**
     * patched indexOf to work in IE 8 and below
     */
    function indexOf(array, needle) {
        if (typeof array !== 'undefined' && array.constructor === Array) {	
            if(typeof Array.prototype.indexOf === 'function') 
	        return array.indexOf(needle);
    
            for(var i=0;i<array.length;++i) 
                if(array[i] === needle) 
                    return i;
        }
    
        return -1;
    }

    /**
     * Initial circular layout
     */
    function circularLayout(nodes, center, radius) {
        var n, _nodes = nodes.filter(function(d) { return !(null != d.x && null != d.y); }); 
 
        for (var i=0;i<_nodes.length;++i) {
	    n = _nodes[i];

            n.x = center.x + radius * Math.sin(2 * Math.PI * i / _nodes.length);
	    n.y = center.y + radius * Math.cos(2 * Math.PI * i / _nodes.length);
        }
    }

    /**
     * validate the variable
     */

    function validate(x, a, b) {
        if (x < a) x = a;
        if (x > b) x = b;
        return x;
    }

    function tick(d) {
	//alert("tick");

        glinks.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

//	gnodes.attr("cx", function (d) { return d.x; })
//	    .attr("cy", function (d) { return d.y; });

        gnodes.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    }



/*node.attr("cx", function (d) {
        return d.x;
    })
        .attr("cy", function (d) {
        return d.y;
    });*/


    function getRadius(d) {
        return d.selected || d.more ? radius * 1.5 : radius; // (d.collapsed && typeof(d.children)!=='undefined') ? 24 : 16;	
    }

    // Color leaf nodes orange, and packages white or blue.
    function getLabel(d) {
	if (d.type==='researcher')
	    return "R";
        if (d.type==='grant') 
            return "G";
        if (d.type==='publication')
            return "P";
        if (d.type==='dataset')
            return "D";
        if (d.type==='institution')
      	    return "I";
	return '';	
    }    

    function getName(d) {
	if (d.type==='researcher')
	    return "Researcher";
        if (d.type==='grant') 
            return "Grant";
        if (d.type==='publication')
            return "Publication";
        if (d.type==='dataset')
            return "Dataset";
        if (d.type==='institution')
      	    return "Institution";
	return '';	
    }
  	

    function getColor(d) {
	if (d.type==='researcher')
	    return "#6ecf9c";
        if (d.type==='grant') 
            return "#fcd57a";
        if (d.type==='publication')
            return "#7ed4fe";
        if (d.type==='dataset')
            return "#fa7d79";
        if (d.type==='institution')
      	    return "#B2B2B2"; //"#545544";

	return "black";
    }

    function getStrokeColor(d) {
        if (d.type==='researcher')
	    return "#64c592";
        if (d.type==='grant') 
            return "#f2cb70";
        if (d.type==='publication')
            return "#74CAF4";
        if (d.type==='dataset')
            return "#F0736F";
        if (d.type==='institution')
	    return "#808080"; //"#4A4B3A";
         
	return "black";
    }

    function getImage(d) {
        if (d.type==='researcher') 
	    return imgPath + "researcher.png";
        if (d.type==='grant')
	    return imgPath + "grant.png";
        if (d.type==='publication')
	    return imgPath + "publication.png";
        if (d.type==='dataset')
	    return imgPath + "dataset.png";
        if (d.type==='institution')
	    return imgPath + "institution.png";

	return ''; 
    }

    function addLegend(index, text, color, strokeColor, image, label) {
	
	var legend = svg.append("g")
	    .attr("class", "graph-legend")
	    .attr("transform", "translate(" + (radius + 5) + "," + (radius * (index * 2.5 + 1) + 5) + ")");

		
	legend.append("circle")
	    .attr("cx", 0)
	    .attr("cy", 0)
	    .attr("r", radius)
    	    .attr("fill", color)
	    .attr("stroke", strokeColor);	
            
	if (null != imgPath)
	    legend.append("image")
		.attr("xlink:href", image)
		.attr("x", -radius/2)
		.attr("y", -radius/2)
		.attr("width", radius + "px")
		.attr("height", radius + "px"); 	
	else
	    legend.append("text")
     		.attr("text-anchor", "middle")
     		.attr("dominant-baseline", "middle")
		//.attr({ position : "absolute", top: "50%", left: "50%" }) // transform: "translateX(-50%) translateY(-50%)
		.text(label);

	legend.append("text")
	    .text(text)
	    .attr("x", radius*1.2)
	    .attr("y", radius/2);
    }

    function initNode() {
       var node = d3.select(document.createElement('div'))
       node.style({
          position: 'absolute',
          opacity: 0,
          pointerEvents: 'none',
          boxSizing: 'border-box'
       })
	    .attr('class', 'graph-tip')	

       return node.node()
    }

    function getPropertiesHtml(d) {
        var html = "<ul class='tip-content'>";

	//html += "<li class='tip-header'><div class='tip-type'>" + d.properties.node_type + ", " + d.properties.node_source + " </div><div class='tip-id'>[" + d.id + "]</div></li>";
          
        for(var p in d.properties) {
	    if (p === 'original_key' || p === 'local_id')
		continue;
	    var name;
	    var property = d.properties[p];
	    if( Object.prototype.toString.call( property ) === '[object Array]' ) {
		property = property.join();
	    } 
	    if (typeof property === 'string' || property instanceof String) {
	    if (property.length > 256) {
		property = property.substring(0, 256) + "...";
	    } 
	    if (p === "key")
		name = "URL";
	    else if (p === "rda_url")
		name = "URL";
	    else if (p === "node_source") {
		name = "Source";
		property = property.toUpperCase();
	    } else if (p === "node_type") {
		name = "Type";
		property = property.toUpperCase();
	    } else if (p === "ands_group") {
		name = "ANDS Group";
	    } else if (p === "doi") {
		name = "DOI";
	    } else if (p === "scopus_eid") {
		name = "Scopus EID";
	    } else if (p === "published_date") {
		name = "Published";
	    } else {
		name = p.split("_").map(function (e) {
		    return e.charAt(0).toUpperCase() + e.substr(1);
		}).join(" ");
	    }

	    html += "<li class='tip-line'><div class='tip-key'>" + name + "</div><div class='tip-value'>" + property + "</div></li>";
	    }
        }
	 
        return html + "</ul>"; 
    }

    function zoomed() {
//	alert("zoomed");
        scale = d3.event.scale
//	alert("scale: " + scale)
        graphContainer.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")")
//       slider.property("value",  d3.event.scale);
    }

    function dragstart(d) {
	d.fixed = true
	d3.event.sourceEvent.stopPropagation();
        d3.select(this).classed("dragging", true);
    }

    function draged(d) {
//	d.px = validate(d.px, 0, width())
//	d.py = validate(d.py, 0, height())
	
	d3.select(this).attr("cx", d.x = d3.event.x).attr("cy", d.y = d3.event.y);
    }

    function dragended(d) {
        d3.select(this).classed("dragging", false);
    }
    
    function openUrl(d) {
	window.open("http://" + d.properties.key, '_blank', 'menubar=yes,titlebar=yes,toolbar=yes,location=yes,scrollbars=yes,status=yes')
    }

/*    function slided(d) {
//	alert("slided: " + zoom);
        zoom.scale(d3.select(this).property("value"))
          .event(zoomContainer);
    }*/

    return graph;
};


$(document).ready(function() {
//    var parser = document.createElement('a');
   
//    parser.href = window.location.href;
   
//    var path = parser.pathname.split("/");
//    if (path.length >= 3) {
    //    var jsonName = "/rda/" + path[1] + "-" + path[2] + ".json";	
//	var jsonName = "http://graph.rd-alliance.org.s3.amazonaws.com/rda/" + path[1] + "-" + path[2] + ".json";
//	var jsonName = "http://rd-switchboard.s3.amazonaws.com/rda/" + path[1] + "-" + path[2] + ".json";
//	var jsonName = "http://rd-switchboard.net/graph/graph.php?graph=" + path[1] + "-" + path[2] + "&accesskey=rdswitchboard";
	var jsonName = "http://rd-switchboard.net/api/graph/index.php?reqkey=" + encodeURIComponent($('#ro_key').val()) + "&accesskey=rdswitchboard";
	d3.json(jsonName, function(error, json) {
            if (null == error) {
        	//alert("json: " + json);
	        var graph = d3.graph();
	        //alert("graph: " + graph);
	        d3.select(".graph")
		    .style("visibility", "visible")
		    .call(graph);
   	        graph.load(json).update();
   	    } else {
	        d3.select(".graph")
		    .attr("class", "graph-void")
		    .style("visibility", "visible")
		    .append("span")
		        .text("Switchboard Widget: This collection has no related party record, grant or connected dataset.");
            }
        });
//    }
});

