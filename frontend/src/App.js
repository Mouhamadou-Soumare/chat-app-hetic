import 'bootstrap/dist/css/bootstrap.min.css';
import {BrowserRouter, Routes, Route} from "react-router-dom";
import NeedAuth from "./Auth/NeedAuth";
import UserList from "./Component/UserList";
import Conversation from "./Component/Conversation";
import Login from "./Auth/Login";
import UserProvider from "./Context/UserContext";

function App() {
    return (
        <UserProvider>
            <BrowserRouter>
                <Routes>
                    <Route path='/' element={
                        <NeedAuth>
                         <div>
                            <div className="w-full h-32" ></div>

                            <div className="container mx-auto">
                                <div className="py-6 h-screen">
                                    <div className="flex border border-grey rounded shadow-lg h-full">
                                    <UserList/>
                                    <Conversation/>
                            </div>
                            </div></div></div>
                        </NeedAuth>
                    }/>
                    <Route path='/login' element={<Login/>}/>
                </Routes>
            </BrowserRouter>
        </UserProvider>
    );
}

export default App;
