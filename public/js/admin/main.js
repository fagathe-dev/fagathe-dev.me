/**
 * @param {SubmitEvent} e
 * @returns {void}
 */
const handleChangePassword = async (e) => {
  e.preventDefault();
  const form = e.target;
  const data = getValues(form);
  const url = form.action;
  const method = form.getAttribute("method") ?? "POST";

  try {
    const res = await fetch(url, { method, body: JSON.stringify(data) });
    const resData = await res.json();
    if (res.ok) {
      if (res.status === 200) {
        form.reset();
        resetValidation(form);
        new Toast("Mot de passe modifié 👍", "success");

        return;
      }
    } else {
      if (res.status === 400) {
        validate(resData.violations, form);
        return;
      }
      console.error(res);
      errorHTTPRequest();
    }
  } catch (error) {
    console.error(error);
    return;
  }
  
};
