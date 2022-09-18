import '../css/app.scss';

import {Dropdown} from 'bootstrap';

document.addEventListener('DOMContentLoaded', ()=>{
    new App();
})

class App {

    constructor(){
        this.enableDropdowns() ;
        this.handleCommentForm() ;
    }

    enableDropdowns(){
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle') ;
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new Dropdown(dropdownToggleEl)) ;
    }

    handleCommentForm(){
        const commentForm = document.querySelector('form.comment-form');

        if(commentForm == null){
            return ;
        }

        commentForm.addEventListener('submit' , async (e) => {
             e.preventDefault();

             const response = await fetch('/ajax/comments' , {
                method: 'POST' ,
                body: new FormData(e.target)
             });

             if(!response.ok){
                return;
             }

             const json = await response.json();

             console.log(json);
        });

    }
}
