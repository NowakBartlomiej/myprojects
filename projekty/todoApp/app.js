const inputBox = document.querySelector('.inputField input');
const addBtn = document.querySelector('.inputField button')
const todoList = document.querySelector('.todoList')
const deleteAllBtns = document.querySelector('.footer button')

inputBox.onkeyup = () => {
    let userData = inputBox.value
    if (userData.trim() != 0) {
        addBtn.classList.add('active')
    } else {
        addBtn.classList.remove('active')
    }
} 


addBtn.onclick = () => {
    let userData = inputBox.value; //getting user entered value
    let getLoclaStorage = localStorage.getItem('New Todo'); // getting localstorage
    if (getLoclaStorage == null) {
        listArr = [];
    } else {
        listArr = JSON.parse(getLoclaStorage); // transforming json string intto a js object
    }
    listArr.push(userData); 
    localStorage.setItem('New Todo', JSON.stringify(listArr)); //trasnsforming js object into a json string
    showTasks();
    
}

// adding tasks
const showTasks = () => {
    let getLoclaStorage = localStorage.getItem('New Todo');
    if (getLoclaStorage == null) {
        listArr = [];
    } else {
        listArr = JSON.parse(getLoclaStorage); // transforming json string intto a js object
    }
    
    const pendingNumber = document.querySelector('.pendingNumber');
    pendingNumber.textContent = listArr.length
    if (listArr.length > 0) {
        deleteAllBtns.classList.add('active')
    } else {
        deleteAllBtns.classList.remove('active');
    }

    let newLiTag = '';
    listArr.forEach((element, index) => {
        newLiTag += `<li>${element}<span onclick="deleteTask(${index})"; ><i class="fas fa-trash"></i></span></li>`;
    });
    todoList.innerHTML = newLiTag
    inputBox.value = '';
    addBtn.classList.remove('active')
}

//delete task
const deleteTask = (index) => {
    let getLoclaStorage = localStorage.getItem('New Todo');
    listArr = JSON.parse(getLoclaStorage); // transforming json string 
    listArr.splice(index, 1)
    
    // After remove the li update the local storage
    localStorage.setItem('New Todo', JSON.stringify(listArr)); //trasnsforming js object into a json string
    showTasks();
}

//delete all task
deleteAllBtns.onclick = () => {
    listArr = [];
    // After delete all tasks update the local storage
    localStorage.setItem('New Todo', JSON.stringify(listArr)); //trasnsforming js object into a json string
    showTasks();
}