window.ProductAction = (function () {
  const { esc } = window.ProductAPI;

  const input = (val, type = "text", nameAttr = "", opts = {}) => {
    const attrs = [
      nameAttr ? `name="${esc(nameAttr)}"` : "",
      type ? `type="${esc(type)}"` : "",
      opts.maxLength ? `maxlength="${esc(opts.maxLength)}"` : "",
      opts.placeholder ? `placeholder="${esc(opts.placeholder)}"` : "",
      'class="form-control form-control-sm dt-inline"',
      `value="${esc(val)}"`,
    ]
      .filter(Boolean) //remove empty/null entries
      .join(" ");

    return `<input ${attrs}>`;
  };

  const select = (
    name,
    options,
    value,
    { placeholder, disabled = false } = {}
  ) => `
    <select class="form-select form-select-sm dt-inline"
            name="${esc(name)}" ${disabled ? "disabled" : ""}>
      ${
        placeholder
          ? `<option value="" ${!value ? "selected" : ""} disabled>${esc(
              placeholder
            )}</option>`
          : ""
      }
      ${options
        .map(
          (o) =>
            `<option value="${esc(o.value)}" ${
              o.value == value ? "selected" : ""
            }>${esc(o.label)}</option>`
        )
        .join("")}
    </select>
  `;

  const actionBtns = (row) => `
    <div class="d-grid gap-2 d-md-block">
      <button type="button" class="btn btn-sm btn-edit custom-btn-bg-products" data-id="${row.id}">
        <i class="fa-solid fa-pen custom-edit-icon-products"></i>
      </button>
      <button type="button" class="btn btn-sm btn-del custom-btn-bg-products" data-id="${row.id}">
        <i class="fa-solid fa-trash custom-edit-icon-products"></i>
      </button>
    </div>`;

  const editBtns = () => `
    <div class="d-grid gap-2 d-md-block">
      <button type="button" class="btn btn-sm btn-save custom-btn-bg-products">
        <i class="fa-solid fa-check custom-check-icon-products fa-lg"></i>
      </button>
      <button type="button" class="btn btn-sm btn-cancel custom-btn-bg-products">
        <i class="fa-solid fa-xmark custom-close-icon-products fa-lg"></i>
      </button>
    </div>`;

  return { input, actionBtns, editBtns, select };
})();
