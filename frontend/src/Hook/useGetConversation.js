import { useEffect, useState } from 'react';


export function useGetMessages():  {

  const { jwt } = useSelector((state: AppState) => ({
    jwt: state.auth,
  }));

  const fetchMessages = async () => {
    const res = await fetch('http://localhost:8245/getconversations', {
      headers: {
        Authorization: `Bearer ${jwt}`,
      },
    });

    if (res) {
      const data = await res.json();
      setMessages(data.messages);
    }
  };

  useEffect(() => {
    fetchConversations();
  }, []);

  return messages;
}