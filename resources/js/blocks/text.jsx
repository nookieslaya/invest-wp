import domReady from '@wordpress/dom-ready';
import { getBlockType, registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';

const BLOCK_NAME = 'invest/text';

const Edit = ({ attributes, setAttributes }) => {
  const { title } = attributes;
  const blockProps = useBlockProps({
    className: 'py-10 md:py-14',
  });

  return (
    <section {...blockProps}>
      <div className="container-main space-y-4">
        <RichText
          tagName="h2"
          className="text-3xl font-semibold text-red-500 md:text-4xl"
          value={title}
          allowedFormats={[]}
          placeholder={__('Add title...', 'sage')}
          onChange={(value) => setAttributes({ title: value })}
        />
      </div>
    </section>
  );
};

const Save = ({ attributes }) => {
  const { title } = attributes;
  const blockProps = useBlockProps.save({
    className: 'py-10 md:py-14',
  });

  return (
    <section {...blockProps}>
      <div className="container-main space-y-4">
        <RichText.Content
          tagName="h2"
          className="text-3xl font-semibold text-blue-500 md:text-4xl"
          value={title}
        />
      </div>
    </section>
  );
};

const registerTextBlock = () => {
  if (getBlockType(BLOCK_NAME)) {
    return;
  }

  registerBlockType(BLOCK_NAME, {
    title: __('Text Block 2', 'sage'),
    description: __('Simple block with title.', 'sage'),
    category: 'text',
    icon: 'editor-textcolor',
    supports: {
      anchor: true,
      align: ['wide', 'full'],
    },
    attributes: {
      title: {
        type: 'string',
        source: 'html',
        selector: 'h2',
      },
    },
    edit: Edit,
    save: Save,
  });
};

domReady(registerTextBlock);
