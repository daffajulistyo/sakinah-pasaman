import React from 'react'
import { NumberedListIcon } from '@heroicons/react/24/outline'

const RecordPerPage = ({
    records=10,
    onchange
}) => {
    const [currentOption, setCurrentOption] = React.useState(10)
    const options = [
        10, 25, 50, 100
    ]

    const onChangeOption = (value) => {
        setCurrentOption(value)
        onchange(value)
    }
    React.useLayoutEffect(() => {
        setCurrentOption(records)
    }, [records])
    return (
        <div className="text-sm font-normal text-gray-500 dark:text-gray-400">
            <button id="dropdownRadioButton" data-dropdown-toggle="dropdownRadio" className="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
                <NumberedListIcon className="h-3 w-3 m-1" />
                {currentOption} Records per Page
                <svg className="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>
            <div id="dropdownRadio" className="z-[99999] hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" 
                data-popper-reference-hidden="" data-popper-escaped="top" data-popper-placement="top" style={{ position: "absolute", inset: "auto auto 0px 0px", margin: "0px", transform: "translate3d(522.5px, 3847.5px, 0px)"}}>
                <ul className="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioButton">
                    {
                        options.map((item, key) => (
                            <li key={key}>
                                <div className="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <input 
                                        onChange={(e) => onChangeOption(e.target.value)} checked={item.toString() === currentOption.toString()}
                                        id={`pilihan-records-${item}`} type="radio" value={item} name="filter-radio" 
                                        className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 
                                        dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 
                                        focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                    <label htmlFor={`pilihan-records-${item}`} className="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">{item} Records</label>
                                </div>
                            </li>
                        ))
                    }
                </ul>
            </div>
        </div>
    )
}

export default RecordPerPage