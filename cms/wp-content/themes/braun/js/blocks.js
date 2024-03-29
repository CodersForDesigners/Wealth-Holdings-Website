
wp.domReady( function() {

	/*
	 *
	 * ----- Only allow specific blocks to be used
	 *
	 */
	let allowedBlockTypes = [
		"core/group",
		"core/column",
		"core/columns",
		"core/heading",
		"core/subhead",
		"core/paragraph",
		"core/quote",
		// "core/pullquote",
		"core/image",
		"core/gallery",
		"core/list",
		"core/separator",
		"core/block",
		"core/spacer"
	];

	const postType = wp.data.select( "core/editor" ).getCurrentPostType()

	if ( postType === "investment" )
		allowedBlocks.push( "acf/bfs-investments" )
	if ( postType === "co-investment" )
		allowedBlocks.push( "acf/bfs-co-investments" )
	if ( postType === "brochure" )
		allowedBlocks.push( "acf/bfs-brochures" )
	if ( postType === "tile-link" )
		allowedBlocks.push( "acf/bfs-tile-link" )
	if ( postType === "testimonial" )
		allowedBlocks.push( "acf/bfs-testimonials" )
	if ( postType === "faq" )
		allowedBlocks.push( "acf/bfs-faqs" )
	if ( postType === "distributor" )
		allowedBlocks.push( "acf/bfs-distributors" )

	let allBlockTypes = wp.blocks.getBlockTypes();
	allBlockTypes.forEach( function ( blockType ) {
		if ( allowedBlockTypes.indexOf( blockType.name ) === -1 )
			wp.blocks.unregisterBlockType( blockType.name );
	} );

} );
