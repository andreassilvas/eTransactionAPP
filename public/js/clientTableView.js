window.ClientTableView = (function () {
  const { esc } = window.ClientAPI;

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

  const actionBtns = (row) => `
    <div class="d-grid gap-2 d-md-block">
      <button type="button" class="btn btn-sm btn-edit custom-btn-bg" data-id="${row.id}">
        <i class="fa-solid fa-pen custom-edit-icon"></i>
      </button>
      <button type="button" class="btn btn-sm btn-del custom-btn-bg" data-id="${row.id}">
        <i class="fa-solid fa-trash custom-edit-icon"></i>
      </button>
    </div>`;

  const editBtns = () => `
    <div class="d-grid gap-2 d-md-block">
      <button type="button" class="btn btn-sm btn-save custom-btn-bg">
        <i class="fa-solid fa-check custom-check-icon"></i>
      </button>
      <button type="button" class="btn btn-sm btn-cancel custom-btn-bg">
        <i class="fa-solid fa-xmark custom-close-icon"></i>
      </button>
    </div>`;

  return { input, actionBtns, editBtns };
})();
