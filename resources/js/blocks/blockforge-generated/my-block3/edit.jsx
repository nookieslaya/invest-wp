import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, URLInput } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

const Edit = ( { attributes, setAttributes } ) => {
	const blockProps = useBlockProps( { className: 'bf-block bg-slate-50 py-16' } );

	return (
		<div {...blockProps}>
			<div className="mx-auto max-w-6xl px-6">
			<div className="grid gap-10 items-center md:grid-cols-2">
				<div className="space-y-6">
				<RichText
						tagName="h2"
						className="text-4xl font-semibold tracking-tight text-slate-900"
						value={ attributes.headline }
						onChange={ ( value ) => setAttributes( { headline: value } ) }
						placeholder="Add a headline..."
					/>
				<RichText
						tagName="p"
						className="text-lg text-slate-600"
						value={ attributes.body }
						onChange={ ( value ) => setAttributes( { body: value } ) }
						placeholder="Add supporting text..."
					/>
				</div>
				<div className="bf-image-wrap">
						<MediaUploadCheck>
							<MediaUpload
								onSelect={ ( media ) =>
									setAttributes( {
										image: {
											id: media.id,
											url: media.url,
											alt: media.alt,
										},
									} )
								}
								allowedTypes={ [ 'image' ] }
								value={ attributes.image?.id }
								render={ ( { open } ) => (
									<Button className="bf-image-button" variant="secondary" onClick={ open }>
										{ attributes.image?.url ? (
											<img className="w-full h-auto object-cover" src={ attributes.image.url } alt={ attributes.image.alt || '' } />
										) : (
											'Select image'
										) }
									</Button>
								) }
							/>
						</MediaUploadCheck>
					</div>
			</div>
			<div className="bf-button-group">
					<a className="inline-flex items-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800" href={ attributes.buttonUrl || '#' }>
						<RichText
							tagName="span"
							value={ attributes.buttonText }
							onChange={ ( value ) => setAttributes( { buttonText: value } ) }
							placeholder="Button text"
						/>
					</a>
					<div className="bf-url-input">
						<URLInput
							value={ attributes.buttonUrl }
							onChange={ ( value ) => setAttributes( { buttonUrl: value } ) }
						/>
					</div>
				</div>
			</div>
		</div>
	);
};

export default Edit;
