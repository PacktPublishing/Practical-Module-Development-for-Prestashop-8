import Grid from '@components/grid/grid';
import FiltersResetExtension from '@components/grid/extension/filters-reset-extension';
import SortingExtension from '@components/grid/extension/sorting-extension';

const $ = window.$;

$(() => {
  const callbackGrid  = new Grid('whcallbackrequest');

  callbackGrid.addExtension(new FiltersResetExtension());
  callbackGrid.addExtension(new SortingExtension());
});
