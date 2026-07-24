import asyncio
import os
from browser_use import Agent
from browser_use.llm import ChatDeepSeek

DEEPSEEK_KEY = os.getenv("DEEPSEEK_API_KEY", "")
BASE_URL = "http://127.0.0.1:8000"


async def test_login():
    llm = ChatDeepSeek(
        base_url='https://api.deepseek.com/v1',
        model='deepseek-chat',
        api_key=DEEPSEEK_KEY,
    )

    agent = Agent(
        llm=llm,
        use_vision=False,
        task=f'''
        Test the SAKINAH login page at {BASE_URL}/auth-admin.

        Steps:
        1. Navigate to {BASE_URL}/auth-admin
        2. Describe what you see on the login page (form fields, buttons, layout)
        3. Report any errors or issues found

        Return a summary of the login page state.
        ''',
    )
    await agent.run()


async def test_home_page():
    llm = ChatDeepSeek(
        base_url='https://api.deepseek.com/v1',
        model='deepseek-chat',
        api_key=DEEPSEEK_KEY,
    )

    agent = Agent(
        llm=llm,
        use_vision=False,
        task=f'''
        Test the SAKINAH home/dashboard page.

        Steps:
        1. Navigate to {BASE_URL}/home
        2. If redirected to login, describe the redirect behavior
        3. If the page loads, describe the content

        Return a summary.
        ''',
    )
    await agent.run()


async def test_public_routes():
    llm = ChatDeepSeek(
        base_url='https://api.deepseek.com/v1',
        model='deepseek-chat',
        api_key=DEEPSEEK_KEY,
    )

    pages = [
        f'{BASE_URL}/',
        f'{BASE_URL}/auth-admin',
        f'{BASE_URL}/home',
        f'{BASE_URL}/token404',
    ]

    agent = Agent(
        llm=llm,
        use_vision=False,
        task=f'''
        Test these SAKINAH pages for status and basic accessibility:

        Pages to check: {', '.join(pages)}

        For each page:
        1. Navigate to the page
        2. Describe what you see
        3. Note any errors or issues

        Return a full report of each page tested.
        ''',
    )
    await agent.run()


async def main():
    print("=" * 50)
    print("Test 1: Login Page")
    print("=" * 50)
    await test_login()

    print("\n" + "=" * 50)
    print("Test 2: Home/Dashboard")
    print("=" * 50)
    await test_home_page()

    print("\n" + "=" * 50)
    print("Test 3: Public Routes")
    print("=" * 50)
    await test_public_routes()


if __name__ == '__main__':
    asyncio.run(main())
