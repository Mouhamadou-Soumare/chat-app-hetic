import {useContext} from "react";
import {userContext} from "../Context/UserContext";


export default function useGetUserList() {
    const storedUser = useContext(userContext);
    
    return function () {
        return fetch('http://localhost:8245/user-list', {
            method: 'GET',
            credentials: 'include',
            mode: "cors",
            headers: {
                'Authorization': `Bearer ${storedUser}`
            }
        })
            .then(data => data.json())
    }
}