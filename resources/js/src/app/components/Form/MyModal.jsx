import React from 'react'
import { Dialog, DialogBackdrop, DialogPanel, DialogTitle } from '@headlessui/react'
import { XMarkIcon } from '@heroicons/react/24/outline'

const MyModal = (
    {
        openModal=false,
        setOpenModal=null,
        ModalTitle="Title",
        size="md",
        children,
    }
) => {
    const [open, setOpen] = React.useState(false)
    React.useEffect(() => {
        setOpen(openModal)
    },[openModal])
    const modalSize = () => {
        if(size == "full") return ""
        else if(size == "lg") return "sm:max-w-7xl"
        else if(size == "sm") return "sm:max-w-xl"
        else return "sm:max-w-3xl"
    }
    const switcherModal = (x) => {
        setOpen(x)
        setOpenModal(x)
    }

    return (
        <Dialog open={open} onClose={switcherModal} className="relative z-[999]">
            <DialogBackdrop transition
                className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity data-[closed]:opacity-0 data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in" />

            <div className="fixed inset-0 z-10 w-screen overflow-y-auto sm:px-6">
                <div className="flex min-h-full items-end justify-center py-4 px-2 text-center sm:items-center sm:p-0">
                    <DialogPanel transition
                        className={`relative transform rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 
                            text-left shadow-xl transition-all data-[closed]:translate-y-4 data-[closed]:opacity-0 
                            data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in sm:my-8 
                            sm:w-full ${modalSize(size)} w-full sm:p-6 data-[closed]:sm:translate-y-0 data-[closed]:sm:scale-95`}>
                        <div>
                            <button onClick={() => switcherModal(false)}
                                className="mx-auto flex h-6 w-6 items-center justify-center rounded-full absolute top-3 right-3">
                                <XMarkIcon aria-hidden="true" className="h-4 w-4 text-gray-400 hover:text-gray-200" />
                            </button>
                            <div className="mt-3 sm:mt-5">
                                <DialogTitle as="h3" className="text-xl font-bold leading-6 text-teal-500 dark:text-white">
                                    {ModalTitle}
                                </DialogTitle>
                            </div>
                        </div>
                        { children }
                        
                    </DialogPanel>
                </div>
            </div>
        </Dialog>
    )
}

export default MyModal