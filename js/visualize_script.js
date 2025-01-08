const inputsWithList = document.querySelectorAll( '.choose_tables_input' );

if ( inputsWithList && inputsWithList.length ) {
	inputsWithList.forEach( function ( /** @type {HTMLInputElement} */ input )
	{
        console.log(input);
		if ( input.type !== 'email' && input.type !== 'file' ) {
			input.addEventListener( 'input', function ( /** @type {Event|InputEvent} */ event )
			{

				/** @type {HTMLInputElement} */
				const input = event.target;

				/** @type {HTMLDataListElement} */
				const datalist = input.list;

				/** @type {HTMLCollection} */
				const options = datalist.options;

				if ( options && options.length ) {
					[ ...options ].forEach( function ( /** @type {HTMLOptionElement} */ option )
					{
						if ( !option.trustedValue ) {
							option.trustedValue = option.value;
							( datalist.allOptionValues = datalist.allOptionValues || new Set() ).add( option.value );
						}
					} );

					/** @type {Boolean} */
					let someValueIsSuffix = false;

					for ( const option of options ) {
						if ( input.value.endsWith( option.trustedValue ) ) {
							someValueIsSuffix = true;
							break;
						}
					}

					/** @type {Array} */
					const parts = input.value.split( ' , ' ).join( ',' ).split( ', ' ).join( ',' ).split( ',' );
					/** @type {String} */
					let lastPart = parts.slice( -1 ).pop();

					if ( lastPart.startsWith( ' ' ) ) {
						lastPart = lastPart.trimStart();
					}
					[ ...options ].forEach( function ( /** @type {HTMLOptionElement} */ option )
					{
						option.hidden = false;
						if ( !someValueIsSuffix && !parts.includes(option.trustedValue)) {
							if ( option.trustedValue.startsWith( lastPart ) ) {
								option.value = input.value + option.trustedValue.replace( lastPart, '' );
							} else {
								option.hidden = true;
							}
						}
					} );
				}
			}, {
				capture: false,
				once: false,
				passive: true,
			} );
		}
	} );
}