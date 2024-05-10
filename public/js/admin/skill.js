/**
 *
 * @param {PointerEvent} e
 *
 * @returns {Toast}
 */
const deleteSkill = async (e) => {
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
