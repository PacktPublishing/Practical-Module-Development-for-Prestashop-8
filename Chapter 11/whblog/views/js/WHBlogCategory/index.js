import TranslatableInput from '@components/translatable-input';

const $ = window.$;

$(() => {
  new TranslatableInput({localeInputSelector: '.js-locale-input'});
});
