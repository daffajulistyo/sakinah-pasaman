import React from 'react'
import Sunnyicon from './Icons/sunnyicon';
import Moonicon from './Icons/moonicon';

const DarkModeSwitcher = () => {
    const [dark, setDark] = React.useState(false);

    const darkModeHandler = () => {
        localStorage.setItem('theme', !dark ? "dark" : "light")
        setDark(!dark);
        document.body.classList.toggle("dark");
    }

    React.useEffect(() => {
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            setDark(true)
        } else {
            setDark(false)
        }
    }, [])

  return (
    <div>
        <button
        onClick={() => darkModeHandler()}
        id="theme-toggle"
        type="button"
        className="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5"
        >
            {
                dark && <Sunnyicon />
            }
            {
                !dark && <Moonicon />
            }
        </button>
    </div>
  )
}

export default DarkModeSwitcher