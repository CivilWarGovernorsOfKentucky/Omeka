

<style>

.links line {
  stroke-opacity: 0.6;
  stroke-width: 1.5px;
}

.nodes circle {
  stroke: #fff;
  stroke-width: 1.5px;
}

.legend rect {
  fill:white;
  stroke:black;
  opacity:0.8;}

 div.tooltip {
  position: absolute;
  text-align: left;
  width: 160px;
  height: 18px;
  padding: 2px;
  font: 8px sans-serif;
}
.svg-container {
    display: inline-block;
    position: relative;
    width: 100%;
    padding-bottom: 100%;
    vertical-align: top;
    overflow: hidden;
}
.svg-content {
    display: inline-block;
    position: absolute;
    top: 0;
    left: 0;
}

</style>
<div id="network-container" class="svg-container">
</div>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.24.0/d3-legend.min.js"></script>
<script>
var container = d3.select("div#network-container"), 
    width = container.node().clientWidth,
    height = container.node().clientHeight;

var svg = d3.select("div#network-container")
  .append("svg")
  .attr("preserveAspectRatio", "xMinYMin meet")
  .attr("viewBox", "0 0 " + width + " " + height)
  .classed("svg-content", true);

var div = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0);

var color = d3.scaleOrdinal(d3.schemeCategory20).domain(["central node", "document", "person", "place", "organization", "familial", "political", "legal", "economic", "social", "military", "slavery"]);

var simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }))
    .force("charge", d3.forceManyBody())
    .force("center", d3.forceCenter(width / 2, height / 2));

d3.json("http://test.mashbill.discovery.civilwargovernors.org/entities/data/<?php echo metadata('item', array('Dublin Core', 'Identifier'))?>", function(error, graph) {
  if (error) throw error;

  var link = svg.append("g")
    .attr("class", "links")
    .selectAll("line")
    .data(graph.links)
    .enter().append("line")
      .attr("stroke-width", function(d) { return Math.sqrt(d.value); })
      .attr("stroke", function(d) { return color(d.group); });

  var node = svg.append("g")
    .attr("class", "nodes")
    .selectAll("circle")
    .data(graph.nodes)
    .enter().append("circle")
      .attr("r", 5)
      .attr("fill", function(d) { return color(d.group); })
      .on("dblclick", dblclick)
      .call(d3.drag()
          .on("start", dragstarted)
          .on("drag", dragged)
          .on("end", dragended)
          )
       .on("mouseover", function(d) {
       div.transition()
         .style("opacity", .9);
       div.html("<a href='<?php echo("http://$_SERVER[HTTP_HOST]/documents/") ?>" + d.id + "'>" + d.id + "<\a><br>" + d.bio)
         .style("left", (d3.event.pageX) + "px")
         .style("top", (d3.event.pageY - 28) + "px");
       })
       .on("mouseout", function(d) {
        div.transition()
        .duration(4000)
        .style("opacity", 0);
       });

  simulation
      .nodes(graph.nodes)
      .on("tick", ticked);

  simulation.force("link")
      .links(graph.links);

svg.append("g")
  .attr("class", "legendOrdinal")
  .attr("transform", "translate(20,20)");

var legendOrdinal = d3.legendColor()
  //d3 symbol creates a path-string, for example
  //"M0,-8.059274488676564L9.306048591020996,
  //8.059274488676564 -9.306048591020996,8.059274488676564Z"
  //.shape("path", d3.symbol().type(d3.symbolTriangle).size(150)())
  //.shapePadding(10)
  //use cellFilter to hide the "e" cell
  .cellFilter(function(d){ return d.label !== "e" })
  .scale(color);

svg.select(".legendOrdinal")
  .call(legendOrdinal);

  function ticked() {
    link
        .attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
  }

});

function dragstarted(d) {
  //d3.select(this).classed("fixed", d.fixed = true);
  if (!d3.event.active) simulation.alphaTarget(0.3).restart();
  d.fx = d.x;
  d.fy = d.y;
}

function dragged(d) {
  d.fx = d3.event.x;
  d.fy = d3.event.y;
}

function dragended(d) {
  if (!d3.event.active) simulation.alphaTarget(0);
  //d.fx = null;
  //d.fy = null;
}

function dblclick(d) {
  d3.select(this).classed("fixed", d.fixed = false);
}

</script>
