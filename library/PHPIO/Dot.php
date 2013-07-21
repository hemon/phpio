<?php 

class PHPIO_Dot {
	var $node = array();
	var $edge = array();
	var $leaf = array();

	function __construct($profile) {
		$this->parse($profile);
	}

	function parse($profile) {
		foreach($profile as $i => $row) {
			if ($i == 0) continue;

			$node_prev = 'MAIN';
			$trace_len = count($row['trace'])-1;
			$classname = $row['classname'];
			foreach($row['trace'] as $j => $trace) {
				// fetch vars
				if ( !preg_match('|(\S+).*\[(.*?)\:(\d+)\]|', $trace, $matches) ) {
					continue;
				}
				list(, $func, $file, $line) = $matches;
				// get source file hash
				if ( isset($profile[0]['_SRC'][ $file ]) ) {
					$file= $profile[0]['_SRC'][ $file ];
				}
				// node
				$node = $file.":".$line;
				$node_label = $func;
				$is_leaf = ($j === $trace_len);
				// Error || Exception is event, didn't made a call, but should draw
				if ( $is_leaf ) {
					if ( in_array($classname,array('Exception','Error')) ) {
						$node = $node.":".$node_label;
					}
				}
				// edge
				$edge = "\"$node_prev\" -> \"$node\"";
				// edge weight
				$this->edge[$edge]++;
				// leaf
				if ( $is_leaf ) {
					// PHPIO only record the leaf node's call times
					$this->leaf[$edge]['calls']++;
					// edge & node color
					$this->leaf[$node]['color'] = $this->leaf[$edge]['color'] = $GLOBALS['_PHPIO']['colors'][$classname];
				}
				$this->node[$node] = $node_label;
				$node_prev = $node;
			}
		}
		return $this;
	}

	function output() {
		$nodes = array();
		foreach ($this->node as $node => $label) {
			list($file, $line) = explode(':', $node);
			$is_leaf = isset($this->leaf[$node]);
			$url = "?op=fileviewer&file=$file&line=$line";
			if ( $is_leaf ) {
				$color = $this->leaf[$node]['color'];
				$nodes[] = "\"$node\" [label=\"$label\", URL=\"$url\", style=filled, fontcolor=white, color=\"$color\"];";
			} else {
				$nodes[] = "\"$node\" [label=\"$label\", URL=\"$url\", style=filled, color=white];";
			}
		}

		$edges = array();
		$maxCount = max($this->edge);
		$maxWeight = 20;
		foreach ($this->edge as $edge => $weight) {
			$penwidth = ceil($weight/$maxCount * $maxWeight); // 1 ~ max
			$style = "penwidth={$penwidth}";

			if ( isset($this->leaf[$edge]) ) {
				$calls = $this->leaf[$edge]['calls'];
				$color = $this->leaf[$edge]['color'];
				$style .= " label=$calls color=\"$color\"";
			}
			$edges[] = "$edge [$style];";
		}
		return 'digraph CallGraph {
//rankdir=LR;
MAIN [shape=doublecircle,fontcolor=white,style=filled, color="#111111"];
'. implode("\n",$nodes)."\n".implode("\n",$edges).'
}';
	}

	function outputHtml() {
		$dot = $this->output();
		echo '
<base target="_blank"/>
<script type="text/vnd.graphviz" id="viz">'.$dot.'</script>
<div id="graphviz"></div>
<script src="js/viz.js"></script>
<script>
document.body.innerHTML = Viz(document.getElementById("viz").innerHTML, "svg").replace("scale(1 1)","scale(.6 .6)");</script>';
	}
}