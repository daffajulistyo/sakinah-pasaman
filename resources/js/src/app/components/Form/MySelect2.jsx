import React from 'react'
import Select from 'react-select'
const MySelect2 = ({
    id,
    options,
    value=null,
    error="",
    label,
    onChange=null,
    placeholder=''
}) => {
    const internalOnChange = (val) => {
        onChange(val)
        setSelectedValue(val)
    }
    React.useEffect(() => { setSelectedValue(value) },[value])
    const [selectedValue, setSelectedValue] = React.useState("")
    return (
        <div className="sm:mb-4 mb-2">
            <label htmlFor={id} 
                className={"block text-sm font-medium leading-6 " + (error !== "" ? "text-red-500" : "text-gray-900 dark:text-white")}>
                {label}
            </label>
            <Select 
                options={options} 
                value={selectedValue} 
                onChange={value => internalOnChange(value)}
                placeholder={placeholder}
            />
        </div>
    )
}

export default MySelect2