import domReady from '@wordpress/dom-ready';
import { getBlockType, registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import {
  MediaUpload,
  MediaUploadCheck,
  RichText,
  useBlockProps,
} from '@wordpress/block-editor';

const BLOCK_NAME = 'invest/hero-basic';
const ALLOWED_MEDIA_TYPES = ['image'];

const Edit = ({ attributes, setAttributes }) => {
  const { title, description, imageUrl, imageAlt, imageId } = attributes;
  const blockProps = useBlockProps({
    className: 'py-12 md:py-16',
  });
  const hasImage = Boolean(imageUrl);
  const wrapperClass = [
    'grid gap-8 rounded-3xl border border-slate-200 bg-white p-6 md:p-10',
    hasImage ? 'md:grid-cols-[1.2fr_0.8fr] md:items-center' : '',
  ]
    .join(' ')
    .trim();

  const onSelectImage = (media) => {
    if (!media || !media.url) {
      setAttributes({
        imageUrl: '',
        imageAlt: '',
        imageId: 0,
      });
      return;
    }

    setAttributes({
      imageUrl: media.url,
      imageAlt: media.alt || '',
      imageId: media.id || 0,
    });
  };

  const onRemoveImage = () => {
    setAttributes({
      imageUrl: '',
      imageAlt: '',
      imageId: 0,
    });
  };

  return (
    <section {...blockProps}>
      <div className="container-main">
        <div className={wrapperClass}>
          <div className="space-y-4">
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
              value={description}
              allowedFormats={[]}
              placeholder={__('Add description...', 'sage')}
              onChange={(value) => setAttributes({ description: value })}
            />
          </div>
          <div className="space-y-3">
            <div className="relative h-56 overflow-hidden rounded-3xl bg-slate-100 md:h-64">
              {hasImage ? (
                <img
                  className="h-full w-full object-cover"
                  src={imageUrl}
                  alt={imageAlt}
                />
              ) : null}
            </div>
            <div className="flex flex-wrap gap-3">
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={onSelectImage}
                  allowedTypes={ALLOWED_MEDIA_TYPES}
                  value={imageId}
                  render={({ open }) => (
                    <button
                      type="button"
                      className="button button-secondary"
                      onClick={open}
                    >
                      {hasImage
                        ? __('Replace image', 'sage')
                        : __('Select image', 'sage')}
                    </button>
                  )}
                />
              </MediaUploadCheck>
              {hasImage ? (
                <button
                  type="button"
                  className="button button-link-delete"
                  onClick={onRemoveImage}
                >
                  {__('Remove image', 'sage')}
                </button>
              ) : null}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

const Save = ({ attributes }) => {
  const { title, description, imageUrl, imageAlt } = attributes;
  const blockProps = useBlockProps.save({
    className: 'py-12 md:py-16',
  });
  const hasImage = Boolean(imageUrl);
  const wrapperClass = [
    'grid gap-8 rounded-3xl border border-slate-200 bg-white p-6 md:p-10',
    hasImage ? 'md:grid-cols-[1.2fr_0.8fr] md:items-center' : '',
  ]
    .join(' ')
    .trim();

  return (
    <section {...blockProps}>
      <div className="container-main">
        <div className={wrapperClass}>
          <div className="space-y-4">
            <RichText.Content
              tagName="h2"
              className="text-3xl font-semibold text-slate-900 md:text-4xl"
              value={title}
            />
            <RichText.Content
              tagName="p"
              className="text-base text-slate-600 md:text-lg"
              value={description}
            />
          </div>
          {hasImage ? (
            <div className="relative h-56 overflow-hidden rounded-3xl bg-slate-100 md:h-64">
              <img
                className="h-full w-full object-cover"
                src={imageUrl}
                alt={imageAlt}
              />
            </div>
          ) : null}
        </div>
      </div>
    </section>
  );
};

const registerHeroBasicBlock = () => {
  if (getBlockType(BLOCK_NAME)) {
    return;
  }

  registerBlockType(BLOCK_NAME, {
    title: __('Hero Basic', 'sage'),
    description: __('Section with title, description, and image.', 'sage'),
    category: 'layout',
    icon: 'format-image',
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
      description: {
        type: 'string',
        source: 'html',
        selector: 'p',
      },
      imageUrl: {
        type: 'string',
        default: '',
      },
      imageAlt: {
        type: 'string',
        default: '',
      },
      imageId: {
        type: 'number',
        default: 0,
      },
    },
    edit: Edit,
    save: Save,
  });
};

domReady(registerHeroBasicBlock);
