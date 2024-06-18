const displayModal = async (event) => {
  event.preventDefault();

  const url = event.target.href;
  const res = await fetch(url);

  if (res.ok) {
    const xp = await res.json();
    let tasks = "";
    for (const task of xp.tasks) {
      tasks += `<li class="list-group-item"><i class="text-primary mdi mdi-check-bold align-middle lh-1 me-2"></i> ${task}</li>`;
    }

    $("#xpName").innerText = xp.name;
    $("#xpPlace").innerText = xp.place;
    $("#xpDuration").innerHTML = `${xp.start_year} - ${
      xp.end_year ?? "aujourd'hui"
    }`;
    $("#xpTasks").innerHTML = tasks;
  }
};
