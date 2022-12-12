import {useEffect, useState} from "react";
import useGetUserList from "../Hook/useGetUserList";
import useBackendPing from "../Hook/useBackendPing";

export default function UserList() {
    const [userList, setUserList] = useState([]);

    const getUserList = useGetUserList();
    const backendPing = useBackendPing();

    const handleSubmit = (e) => {
        e.preventDefault();
        const userId = e.target[0].value;
        backendPing(userId).then(data => console.log(data))
    }

    const handleMessage = (e) => {
        document.querySelector('h1').insertAdjacentHTML('afterend', '<div class="alert alert-success w-75 mx-auto">Ping !</div>');
        window.setTimeout(() => {
            const $alert = document.querySelector('.alert');
            $alert.parentNode.removeChild($alert);
        }, 2000);
        console.log(JSON.parse(e.data));
    }

    useEffect(() => {
        getUserList().then(data => setUserList(data.users));

        const url = new URL('http://localhost:9090/.well-known/mercure');
        url.searchParams.append('topic', 'https://example.com/my-private-topic');

        const eventSource = new EventSource(url, {withCredentials: true});
        eventSource.onmessage = handleMessage;

        return () => {
            eventSource.close()
        }

    }, [])

    
    return (
    <div className="flex flex-row justify-between bg-white" style={{width: "720px"}}>
      <div className="flex flex-col border-r-2 overflow-y-auto">
        <div className="border-b-2 py-4 px-2">
        <h1 className='m-5 text-center'>Ping a user</h1>
        </div>
        {userList.map((user) => (
        <form className="w-full" onSubmit={handleSubmit}>
        <div className="text-lg font-semibold"><button value={user.id} style={{width: "-webkit-fill-available"}}>
        <div
          className="flex flex-row py-4 px-2 justify-center items-center border-b-2"
        >
          <div className="w-1/4">
            <img
              src="https://source.unsplash.com/_7LbC5J-jw4/600x600"
              className="object-cover h-12 w-12 rounded-full"
              alt=""
            />
          </div>
          <div className="w-full">
            <div className="text-lg font-semibold">{user.username}</div>
            <span className="text-gray-500">Pick me at 9:00 Am</span>
          </div>
        </div>
        </button></div>
        </form>

        ))}

      </div>
      </div>
    )
}