import domReady from '@wordpress/dom-ready';
import { getBlockType, registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';

const BLOCK_NAME = 'invest/text-basic';

const Edit = ({ attributes, setAttributes }) => {
  const { title, text } = attributes;
  const blockProps = useBlockProps({
    className: 'py-10 md:py-14',
  });

  return (
    <section {...blockProps}>
      <div className="container-main space-y-4">
        <RichText
          tagName="h2"
          className="text-3xl font-semibold text-slate-900 md:text-4xl"
          value={title}
          allowedFormats={[]}
          placeholder={__('Add title...', 'sage')}
          onChange={(value) => setAttributes({ title: value })}
        />
        <RichText
          tagName="p"
          className="text-base text-slate-600 md:text-lg"
          value={text}
          allowedFormats={[]}
          placeholder={__('Add text...', 'sage')}
          onChange={(value) => setAttributes({ text: value })}
        />
      </div>
    </section>
  );
};

const Save = ({ attributes }) => {
  const { title, text } = attributes;
  const blockProps = useBlockProps.save({
    className: 'py-10 md:py-14',
  });

  return (
    <section {...blockProps}>
      <div className="container-main space-y-4">
        <RichText.Content
          tagName="h2"
          className="text-3xl font-semibold text-slate-900 md:text-4xl"
          value={title}
        />
        <RichText.Content
          tagName="p"
          className="text-base text-slate-600 md:text-lg"
          value={text}
        />
      </div>
    </section>
  );
};

const registerTextBasicBlock = () => {
  if (getBlockType(BLOCK_NAME)) {
    return;
  }

  registerBlockType(BLOCK_NAME, {
    title: __('Text Basic', 'sage'),
    description: __('Simple block with title and text.', 'sage'),
    category: 'text',
    icon: 'editor-textcolor',
    supports: {
      anchor: true,
      align: ['wide', 'full'],
    },
    // Atrybuty zapisywane w tresci wpisu.
    attributes: {
      title: {
        type: 'string',
        source: 'html',
        selector: 'h2',
      },
      text: {
        type: 'string',
        source: 'html',
        selector: 'p',
      },
    },
    edit: Edit,
    save: Save,
  });
};

domReady(registerTextBasicBlock);
