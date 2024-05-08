$('#add-task').addEventListener("click", addFormToCollection);

/**
 * @param {PointerEvent} e
 * 
 * @return void
 */
function addFormToCollection(e) {
  const widgetContainer = $(e.currentTarget.dataset.listSelector);
  let index = parseInt(widgetContainer.dataset.widgetCounter ?? '1');
  
  let newWidget = widgetContainer.dataset.prototype.replaceAll(/__name__/g, index);
  index++;

  widgetContainer.dataset.widgetCounter = index;

  // Entourer le widget d'un HTML customisé
  newWidget =
    `<div class="row mb-3">
      <div class="col-11">
        ${newWidget}
      </div>
      <div class="col-1">
        <span class="btn btn-delete text-danger" onclick="deleteTask(event)"><i class="bi bi-x-square"></i></span>
      </div>
    </div>`;

  return widgetContainer.insertAdjacentHTML('beforeend', newWidget);
};

// Supprimer un task du DOM
deleteTask = (e) => e.target.closest('.row').remove();