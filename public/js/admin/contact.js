const addTaskBtn = $("#add-task");

if (addTaskBtn !== undefined) {
  addTaskBtn.addEventListener("click", addFormToCollection);
}

/**
 * @param {PointerEvent} e
 *
 * @return void
 */
function addFormToCollection(e) {
  const widgetContainer = $(e.currentTarget.dataset.listSelector);
  let index = parseInt(widgetContainer.dataset.widgetCounter ?? "1");

  let newWidget = widgetContainer.dataset.prototype.replaceAll(
    /__name__/g,
    index
  );
  index++;

  widgetContainer.dataset.widgetCounter = index;

  // Entourer le widget d'un HTML customisé
  newWidget = `<div class="row mb-3">
      <div class="col-11">
        ${newWidget}
      </div>
      <div class="col-1">
        <span class="btn btn-delete text-danger" onclick="deleteTask(event)"><i class="bi bi-x-square"></i></span>
      </div>
    </div>`;

  return widgetContainer.insertAdjacentHTML("beforeend", newWidget);
}

// Supprimer un task du DOM
const deleteTask = (e) => e.target.closest(".row").remove();

/**
 *
 * @param {PointerEvent} e
 *
 * @returns {Toast}
 */
const deleteExperience = async (e) => {
  e.preventDefault();
  const link = e.target.tagName === "I" ? e.target.closest("a") : e.target;
  const url = link.href;
  const method = "DELETE";

  try {
    const res = await fetch(url, { method });
    if (res.ok) {
      if (res.status === 204) {
        link.closest("tr").remove();

        return new Toast("Expérience supprimée 👍", "success");
      }
    } else {
      if (res.status === 500) {
        const resData = await res.json();

        return new Toast(resData.message, "danger");
      }
      console.error(res);

      return errorHTTPRequest();
    }
  } catch (error) {
    console.error(error);
    return errorHTTPRequest();
  }
};

const url = new URL(location.href);
const searchParams = url.searchParams

if (searchParams?.size > 0) {
  searchParams.forEach((v, k) => {
    if (v === "") {
      searchParams.delete(k);
      window.history.pushState({}, '', url)
    }
    const field = document.querySelector(`[name="${k}"]`);
    if (field instanceof Element) {
      field.querySelector(`option[value="${v}"]`).selected = true;
    }
  });
}
