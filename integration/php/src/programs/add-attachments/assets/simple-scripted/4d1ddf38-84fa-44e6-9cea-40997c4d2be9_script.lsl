integer i = 1;

default
{
    state_entry()
    {
        llSay(0, "Script running");
    }
    
    touch_start(integer n)
    {
        llSay(0, "Touch " + i++);
    }
}