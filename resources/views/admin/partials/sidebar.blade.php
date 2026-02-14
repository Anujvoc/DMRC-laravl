<!-- Sidebar -->
<style>
.app-menubar {
    width: 260px;
    height: 100vh;
    background: #2f4154;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    font-family: 'Segoe UI', sans-serif;
}

/* Logo area */
.sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.sidebar-header img {
    width: 150px;
}

/* Menu */
.menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu li {
    width: 100%;
}

/* Main Links */
.menu > li > a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    color: #dce3ea;
    text-decoration: none;
    font-size: 15px;
    transition: 0.3s;
}

.menu > li > a i {
    margin-right: 12px;
}

.menu > li > a span {
    flex: 1;
}

/* Hover */
.menu > li > a:hover {
    background: #3d5368;
    color: #fff;
}

/* Arrow */
.arrow {
    border: solid #dce3ea;
    border-width: 0 2px 2px 0;
    display: inline-block;
    padding: 4px;
    transform: rotate(45deg);
    transition: 0.3s;
}

/* Submenu */
.submenu {
    list-style: none;
    padding: 0;
    display: none;
    background: #263746;
}

.submenu li a {
    display: block;
    padding: 10px 50px;
    font-size: 14px;
    color: #c8d3dc;
    text-decoration: none;
    transition: 0.3s;
}

.submenu li a:hover {
    background: #32495f;
    color: #fff;
}

/* Active */
.has-sub.active > .submenu {
    display: block;
}

.has-sub.active > a .arrow {
    transform: rotate(-135deg);
}
</style>
<aside class="app-menubar">
    @include('admin.partials.header')
  

    <ul class="menu">

        <li>
            <a href="#">
               <i class="fi fi-rr-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Master Management</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.master.companies') }}">Companies</a></li>
                <li><a href="{{ route('admin.master.designations') }}">Designations</a></li>
                <li><a href="{{ route('admin.master.training_types') }}">Training Types</a></li>
                <li><a href="{{ route('admin.master.venues') }}">Venues</a></li>
                 <li><a href="{{ route('admin.master.holidays') }}">Holidays</a></li>
            </ul>
        </li>

        <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Timetable management</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{url('admin/timetable-management/calender/batch')}}">Manage Timetable</a></li>
                <li><a href="#">Add Timetable Entry</a></li>
                <li><a href="#">Teacher Replacement</a></li>
                <li><a href="#">Daily Report</a></li>
            </ul>
        </li>

        <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Training Batch Management</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.training_batch.index') }}">Manage Batches</a></li>
                <li><a href="{{ route('admin.training_batch.create') }}">Add Batch</a></li>
                <li><a href="#">Analytics Dashboard</a></li>
                <li><a href="#">Compare Batches</a></li>
            </ul>
        </li>

            <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Modules</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
               <li><a href="{{ route('admin.module.topics') }}">Topics</a></li>
                <li><a href="{{ route('admin.module.subtopics') }}">Sub Topics</a></li>
                <li><a href="{{ route('admin.module.master') }}">Module Master</a></li>
                <li><a href="#">Add Module</a></li>
                <li><a href="#">All Modules</a></li>
                <li><a href="#">Manage Categories</a></li>
            </ul>
        </li>

         <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Topics</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">All Topics </a></li>
                <li><a href="#">Add Topics</a></li>
                <li><a href="#">Manage Topics</a></li>
                
            </ul>
        </li>


      
         <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>default.trainees</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">add trainees</a></li>
                <li><a href="#">all trainees</a></li>
                <li><a href="#">active trainees</a></li>
                <li><a href="#">Bulk Enroll</a></li>
                <li><a href="#">Import/Export</a></li>
                <li><a href="#">manage trainees</a></li>
               

                
            </ul>
        </li>
           <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>default.veneue-management</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">add venues</a></li>
                <li><a href="#">all venues</a></li>
                <li><a href="#">manage venues-group</a></li>
              
               

                
            </ul>
        </li>

           <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Attendance</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Attendance</a></li>
                <li><a href="#">Attendance(Bulk)</a></li>
                <li><a href="#">Attendance(Import)</a></li>
                <li><a href="#">Certificate List</a></li>
                <li><a href="#">Attendance Dates</a></li>
                
              
               

                
            </ul>
        </li>

           <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Attendance</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Attendance</a></li>
                <li><a href="#">Attendance(Bulk)</a></li>
                <li><a href="#">Attendance(Import)</a></li>
                <li><a href="#">Certificate List</a></li>
                <li><a href="#">Attendance Dates</a></li>
                
              
               

                
            </ul>
        </li>
        
           <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Assessment</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Add Assessment</a></li>
                  <li><a href="#">View all Assessment</a></li>
               
                
              
               

                
            </ul>
        </li>

                   <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Rivision Notes</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Add Notes</a></li>
                  <li><a href="#">View Archive</a></li>
               
                
              
               

                
            </ul>
        </li>

        
                   <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Downloads</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Create Download</a></li>
                  <li><a href="#">All Download</a></li>
               
                
              
               

                
            </ul>
        </li>

                           <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Discussions</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Instructor Chat</a></li>
                  <li><a href="#">All Download</a></li>
               
                
              
               

                
            </ul>
        </li>

        <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Discussions</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Instructor Chat</a></li>
                  <li><a href="#">All Download</a></li>
               
                
              
               

                
            </ul>
        </li>
                <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Test</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Add Test</a></li>
                  <li><a href="#">All Test</a></li>
               
                
              
               

                
            </ul>
        </li>
          <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Surveys</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Add Surveys</a></li>
                  <li><a href="#">All Surveys</a></li>
               
                
              
               

                
            </ul>
        </li>

                 <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Cerificates</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Create Certificate</a></li>
                  <li><a href="#">Manage Certificate</a></li>
                  <li><a href="#">Track Certificate</a></li>
               
                
              
               

                
            </ul>
        </li>

         <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Cerificates</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Create Certificate</a></li>
                  <li><a href="#">Manage Certificate</a></li>
                  <li><a href="#">Track Certificate</a></li>
               
                
              
               

                
            </ul>
        </li>
              <li>
            <a href="#">
               <i class="fi fi-rr-home"></i>
                <span>Reports</span>
            </a>
        </li>

                 <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Blogs</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Add Post</a></li>
                  <li><a href="#">view Archive</a></li>
                 
               
                
              
               

                
            </ul>
        </li>
        <li>
            <a href="#">
               <i class="fi fi-rr-home"></i>
                <span>File Manager</span>
            </a>
        </li>
          <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Articles</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">Add Articles</a></li>
                  <li><a href="#">view Articles</a></li>
                 
               
                
              
               

                
            </ul>
        </li>
         <li>
            <a href="#">
               <i class="fi fi-rr-home"></i>
                <span>File Manager</span>
            </a>
        </li>
        

    </ul>
</aside>
 
<script>
document.addEventListener("DOMContentLoaded", function () {

    const menuItems = document.querySelectorAll(".has-sub > a");

    menuItems.forEach(item => {
        item.addEventListener("click", function (e) {
            e.preventDefault();

            const parent = this.parentElement;
            const submenu = parent.querySelector(".submenu");

            // Close other open menus
            document.querySelectorAll(".has-sub").forEach(menu => {
                if (menu !== parent) {
                    menu.classList.remove("active");
                    const otherSub = menu.querySelector(".submenu");
                    if (otherSub) {
                        otherSub.style.maxHeight = null;
                    }
                }
            });

            // Toggle current menu
            parent.classList.toggle("active");

            if (submenu.style.maxHeight) {
                submenu.style.maxHeight = null;
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
            }
        });
    });

});
</script>


