import React from 'react'
import { MultiSelect } from 'react-multi-select-component'

const MyMultiSelect = ({
    id,
    options,
    value=[],
    error="",
    label,
    onChange=null,
    isCreatable = false
}) => {
    const internalOnChange = (val) => {
        setSelectedValue(val)
        onChange(val)
    }
    React.useEffect(() => {
        if(JSON.stringify(value) !== JSON.stringify(selectedValue)){ 
            setSelectedValue(value)
        }
        
    },[value])
    const [selectedValue, setSelectedValue] = React.useState([])
    return (
        <div className="sm:mb-4 mb-2">
            <label htmlFor={id} 
                className={"block text-sm font-medium leading-6 " + (error !== "" ? "text-red-500" : "text-gray-900 dark:text-white")}>
                {label}
            </label>
            <MultiSelect
                options={options}
                value={selectedValue}
                onChange={internalOnChange}
                labelledBy={label}
                isCreatable={isCreatable}
            />
        </div>
    )
}

export default MyMultiSelect