document.addEventListener("DOMContentLoaded",function(){
    mypath=window.location.pathname.split('/');
    list_paths=['index.php','about.php','abouts.php','contact.php','portfolio.php','projects.php',"services.php",'service.php','testimonials.php']
    path_link=mypath[mypath.length-1];
    list_paths.forEach(element => {
        if(element === path_link){
        } 
    });
    const nav_bar=document.querySelector('header .desktop-nav');
    const nav_bar_btn=nav_bar.querySelectorAll('a');
    
    nav_bar_btn.forEach(element=>{
        element.classList.remove('active');
        if (element.href.split('/')[element.href.split('/').length-1].toLowerCase() === path_link.toLowerCase() || element.href.toLocaleLowerCase()===path_link) {
            element.classList.add('active');
            console.log(element)
        }  
        console.log(element)   
    });
    
});