var url="assets/json/",allJobList="",prevButton=document.getElementById("page-prev"),nextButton=document.getElementById("page-next"),currentPage=1,itemsPerPage=8,getJSON=function(e,t){var a=new XMLHttpRequest;a.open("GET",url+e,!0),a.responseType="json",a.onload=function(){var e=a.status;t(200===e?null:e,a.response)},a.send()};function loadJobListData(e,t){var a=Math.ceil(e.length/itemsPerPage);t<1&&(t=1),t>a&&(t=a),document.querySelector("#job-list").innerHTML="",1==currentPage?(itemsPerPage=7,document.querySelector("#job-list").insertAdjacentHTML("afterbegin",'<div class="col-lg-3 col-md-6" id="job-widget">        <div class="card card-height-100 bg-info bg-job">            <div class="card-body p-5">                <h2 class="lh-base text-white">Velzon invites young professionals for an intership!</h2>                <p class="text-white text-opacity-75 mb-0 fs-14">Don\'t miss your opportunity to improve your skills!</p>                <div class="mt-5 pt-2">                    <button type="button" class="btn btn-light w-100">View More <i class="ri-arrow-right-line align-bottom"></i></button>                </div>            </div>        </div>    </div>')):itemsPerPage=8;for(var n=(t-1)*itemsPerPage;n<t*itemsPerPage&&n<e.length;n++){var i="";e[n]&&Array.from(e[n].requirement).forEach((function(e,t){var a="";e&&(a="Full Time"==e?"bg-success-subtle text-success":"Freelance"==e?"bg-primary-subtle text-primary":"Urgent"==e?"bg-danger-subtle text-danger":"Part Time"==e?"bg-warning-subtle text-warning":"Private"==e?"bg-info-subtle text-info":"bg-success-subtle text-success"),i+='<span class="badge '+a+'">'+e+"</span>"})),e[n]&&(document.querySelector("#job-list").innerHTML+='<div class="col-lg-3 col-md-6">        <div class="card">            <div class="card-body">                <button type="button" class="btn btn-icon btn-soft-primary float-end" data-bs-toggle="button" aria-pressed="true"><i class="mdi mdi-cards-heart fs-16"></i></button>                <div class="avatar-sm mb-4">                    <div class="avatar-title bg-light rounded">                        <img src="'+e[n].companyLogo+'" alt="" class="avatar-xxs" />                    </div>                </div>                <a href="#!"><h5>'+e[n].jobTitle+'</h5></a>                <p class="text-muted">'+e[n].companyName+'</p>                <div class="d-flex gap-4 mb-3">                    <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> '+e[n].location+'</div>                    <div><i class="ri-time-line text-primary me-1 align-bottom"></i> '+e[n].postDate+'</div>                </div>                <p class="text-muted">'+e[n].description+'</p>                <div class="hstack gap-2">'+i+'</div>                <div class="mt-4 hstack gap-2">                    <a href="#!" class="btn btn-soft-primary w-100">Apply Job</a>                    <a href="apps-job-details.html" class="btn btn-soft-success w-100">Overview</a>                </div>            </div>        </div>    </div>')}document.getElementById("total-result").innerHTML=e.length,selectedPage();var s=document.getElementById("searchJob");s.addEventListener("keyup",(function(){s.value.toLowerCase().length>0?document.getElementById("job-widget").style.display="none":document.getElementById("job-widget").style.display="block"})),e.length>0?document.getElementById("job-widget")&&(document.getElementById("job-widget").style.display="block"):document.getElementById("job-widget")&&(document.getElementById("job-widget").style.display="none"),1==currentPage?prevButton.parentNode.classList.add("disabled"):prevButton.parentNode.classList.remove("disabled"),currentPage==a?nextButton.parentNode.classList.add("disabled"):nextButton.parentNode.classList.remove("disabled")}function selectedPage(){for(var e=document.getElementById("page-num").getElementsByClassName("clickPageNumber"),t=0;t<e.length;t++)t==currentPage-1?e[t].parentNode.classList.add("active"):e[t].parentNode.classList.remove("active")}function paginationEvents(){var e=function(){return Math.ceil(allJobList.length/itemsPerPage)};prevButton.addEventListener("click",(function(){currentPage>1&&(currentPage--,loadJobListData(allJobList,currentPage))})),nextButton.addEventListener("click",(function(){currentPage<e()&&(currentPage++,loadJobListData(allJobList,currentPage))})),function(){var t=document.getElementById("page-num");t.innerHTML="";for(var a=1;a<e()+1;a++)t.innerHTML+="<div class='page-item'><a class='page-link clickPageNumber' href='javascript:void(0);'>"+a+"</a></div>"}(),document.addEventListener("click",(function(e){"A"==e.target.nodeName&&e.target.classList.contains("clickPageNumber")&&(currentPage=e.target.textContent,loadJobListData(allJobList,currentPage))})),selectedPage()}getJSON("job-grid-list.json",(function(e,t){null!==e?console.log("Something went wrong: "+e):(loadJobListData(allJobList=t,currentPage),paginationEvents())}));var searchElementList=document.getElementById("searchJob");function filterData(){var e=document.getElementById("idStatus").value,t=document.getElementById("datepicker").value,a=document.getElementById("idType").value,n=t.split(" to ")[0],i=t.split(" to ")[1],s=allJobList.filter((function(s){var l=s.status,r=!1,o=!1,c=!1;return r="all"==l||"all"==e||l==e,s.requirement.map((function(e){c="all"==e||"all"==a||s.requirement.includes(a)})),o=new Date(s.postDate)>=new Date(n)&&new Date(s.postDate)<=new Date(i),r&&c&&o?r&&c&&o:r&&c&&""==t?r&&c:c&&o&&""==t?c&&o:void 0})),l=document.getElementById("page-num");l.innerHTML="";for(var r=Math.ceil(s.length/itemsPerPage),o=1;o<r+1;o++)l.innerHTML+="<div class='page-item'><a class='page-link clickPageNumber' href='javascript:void(0);'>"+o+"</a></div>";loadJobListData(s,currentPage)}searchElementList.addEventListener("keyup",(function(){var e=searchElementList.value.toLowerCase();var t,a=(t=e,allJobList.filter((function(e){return-1!==e.jobTitle.toLowerCase().indexOf(t.toLowerCase())})));0==a.length?document.getElementById("pagination-element").style.display="none":document.getElementById("pagination-element").style.display="flex";var n=document.getElementById("page-num");n.innerHTML="";for(var i=Math.ceil(a.length/itemsPerPage),s=1;s<i+1;s++)n.innerHTML+="<div class='page-item'><a class='page-link clickPageNumber' href='javascript:void(0);'>"+s+"</a></div>";loadJobListData(a,currentPage)}));