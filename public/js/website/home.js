const displayProjectModal = async (event) => {
  event.preventDefault();

  const url = event.target.href;
  const res = await fetch(url);

  if (res.ok) {
    const project = await res.json();
    let tasks = "";

    for (const task of project.tasks) {
      tasks += `<li class="list-group-item"><i class="text-primary mdi mdi-check-bold align-middle lh-1 me-2"></i> ${task}</li>`;
    }

    $("#projectName").innerText = project.name;
    $("#projectDescription").innerHTML = project.description;
    if (project.url !== null) {
      $("#projectUrl").href = project.url;
      $("#projectUrl").classList.remove('d-none');
    } else {
      $("#projectUrl").classList.add('d-none');
    }
    $("#projectImage").src = project.image;
    $("#projectImage").srcset = project.image;
    $("#projectImage").alt = `Image du projet ${project.name}`;
    $("#projectImage").title = `Image du projet ${project.name}`;
    
    $("#projectTasks").innerHTML = tasks;
  }
};

const displayXpModal = async (event) => {
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