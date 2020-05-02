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