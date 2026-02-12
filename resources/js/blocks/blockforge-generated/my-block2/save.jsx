import { useBlockProps, RichText } from '@wordpress/block-editor';

const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save( { className: 'bf-block bg-slate-50 py-16' } );

	return (
		<div {...blockProps}>
			<div className="mx-auto max-w-6xl px-6">
			<div className="grid gap-10 items-center md:grid-cols-2">
				<div className="space-y-6">
				<RichText.Content
						tagName="h2"
						className="text-4xl font-semibold tracking-tight text-slate-900"
						value={ attributes.headline }
					/>
				<RichText.Content
						tagName="p"
						className="text-lg text-slate-600"
						value={ attributes.body }
					/>
				</div>
				{ attributes.image?.url && (
						<div className="bf-image-wrap">
							<img className="w-full h-auto object-cover" src={ attributes.image.url } alt={ attributes.image.alt || '' } />
						</div>
					) }
			</div>
			{ attributes.buttonUrl && (
					<div className="bf-button-group">
						<a className="inline-flex items-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800" href={ attributes.buttonUrl }>
							<RichText.Content tagName="span" value={ attributes.buttonText } />
						</a>
					</div>
				) }
			</div>
		</div>
	);
};

export default Save;
