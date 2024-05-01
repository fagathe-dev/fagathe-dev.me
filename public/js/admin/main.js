/**
 * @param {SubmitEvent} event
 * @returns {void}
 */
const handleChangePassword = async (e) => {
  e.preventDefault();
  const form = e.target;
  const data = getValues(form);
  const url = form.action;
  const method = form.getAttribute("method");

  try {
    const res = await fetch(url, { method });
    if (res.ok) {
      const resData = await res.json();
      if (res.status === 204) {
        new Toast("Mot de passe modifié 👍", "success");

        return;
      }
      if (res.status === 400) {
        validateAll(resData);
      }
    } else {
      console.error(res);
      errorHTTPRequest();
    }
  } catch (error) {
    return;
  }
  console.info({ form, data, url });
};
