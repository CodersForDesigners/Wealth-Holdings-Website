
wp.domReady( function () {

if ( wp.data.select( "core/editor" ).getCurrentPostType() !== "faq" )
	return;





/*
 *
 * State
 *
 */
const PLUGIN_NAME = "bfs-set-parent-post-for-faq-plugin";


function DocumentSettingsPanelWidget () {
	return el( PluginDocumentSettingPanel,
		{ title: "Associated FAQ", panelName: "document-panel-associated-faq", opened: true, className: "document-panel-associated-faq", icon: "..." },
		el( FAQSelector )
	);
}

registerPlugin( PLUGIN_NAME, {
	render: DocumentSettingsPanelWidget
} );



} );
