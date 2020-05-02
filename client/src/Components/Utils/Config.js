import { toast } from 'react-toastify';
// const helpers = {
//     fetchApi: function(){

//     }
// }

// export const userLogin = (user) => ({
//     type: types.AUTH_LOGIN,
//     user
// });
export const assets = 'yuuu';

export function headers() {
    return { 'Content-Type': 'application/json' };
}

/*
การเรียกใช้งาน
1.import { authHeader, assets } from '../Config'; 

2.สามารถเรียกใช้ authHeader() < function, assets < ตัวแปล
*/
export function showToast(type, title, autoClose=1500) {
    switch(type){
        case 'success':{
            toast.success(title , {containerId: 'toast_container_id', autoClose});
        break;
        }

        case 'error':{
            toast.error(title , {containerId: 'toast_container_id', autoClose});
        break;
        }

        case 'warn':{
            toast.warn(title , {containerId: 'toast_container_id', autoClose});
        break;
        }

        case 'info':{
            toast.info(title , {containerId: 'toast_container_id', autoClose});
        break;
        }
    }   
}

export function isEmpty(obj) {

    // null and undefined are "empty"
    if (obj == null) return true;

    // Assume if it has a length property with a non-zero value
    // that that property is correct.
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;

    // If it isn't an object at this point
    // it is empty, but it can't be anything *but* empty
    // Is it empty?  Depends on your application.
    if (typeof obj !== "object") return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}