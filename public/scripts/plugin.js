/**
 * @author crynobone
 */

Js.plugin = {};
Js.data.plugin = {};

Js.data.plugin.showDialog = 0;
Js.plugin.showDialog = function( object ) {
	var node = new Js.widget.dialog({
		element: "plugin-showdialog-" + (Js.data.plugin.showDialog++),
		renderTo: "#wrapper",
		title: object.title,
		width: 400,
		content: object.content
	});
};
