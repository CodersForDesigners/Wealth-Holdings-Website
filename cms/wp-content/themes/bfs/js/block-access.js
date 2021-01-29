
wp.domReady( function () {

	let postType = wp.data.select( "core/editor" ).getCurrentPostType();
	let allowedBlocks = [
		"core/paragraph",
		"core/image",
		"core/heading",
		"core/subhead",
		"core/gallery",
		"core/list",
		"core/group",
		"core/separator",
		"core/spacer",
		"acf/bfs-form"
	];
	if ( postType === "investment" )
		allowedBlocks.push( "acf/bfs-investments" )
	if ( postType === "brochure" )
		allowedBlocks.push( "acf/bfs-brochures" )
	if ( postType === "testimonial" )
		allowedBlocks.push( "acf/bfs-testimonials" )
	if ( postType === "faq" )
		allowedBlocks.push( "acf/bfs-faqs" )

	wp.blocks.getBlockTypes()
		.map( block => block.name )
		.forEach( function ( block ) {
			if ( ! allowedBlocks.includes( block ) )
				wp.blocks.unregisterBlockType( block );
		} );

} );
