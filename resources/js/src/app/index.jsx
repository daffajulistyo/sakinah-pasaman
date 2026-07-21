import React from 'react'
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import { Public, Private } from './routes'
import Page404 from './pages/error/Page404';
const App = () => {
    React.useEffect(() => {
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            localStorage.setItem('theme', "dark");
            document.body.classList.add("dark");
        } else {
            localStorage.setItem('theme', "light");
            document.body.classList.remove("dark");
        }
    }, [])

    const unTrackPath = ['/', '/authenticating']
    const thisUrl = window.location.pathname + window.location.search
    React.useEffect(() => {
        if(!unTrackPath.includes(thisUrl))
        {
            localStorage.setItem('lastPath', thisUrl)
        }
        else localStorage.setItem('lastPath', "")
    },[thisUrl])

    return (
        <BrowserRouter>
            <Routes>
                {
                    Public.map(({ key, name, Component, path}) => (
                        <Route name={name} key={key} path={path} element={<Component />} />
                    ))
                }
                {
                    Private.map(({ key, name, Component, path}) => (
                        <Route name={name} key={key} path={path} element={<Component />} />
                    ))
                }
                <Route name="errorPage404" key="errorPage404" path="/*" element={<Page404 />} />
            </Routes>
        </BrowserRouter>
    )
}

export default App
