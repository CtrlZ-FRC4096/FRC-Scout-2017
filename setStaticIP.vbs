Dim StdIn, StdOut,strComputer
Set StdIn = WScript.StdIn
Set StdOut = WScript.StdOut
strComputer = "." 
Set objWMIService = GetObject("winmgmts:{impersonationLevel=impersonate}!\\" & strComputer & "\root\cimv2") 

Function getSpaces(length,padChar)

   Dim str
   str=""

   For i = 1 To length
     str = str & padChar   
   Next    
   getSpaces = str
End Function


Function Lpad (inputStr, padChar, lengthStr)  
    Lpad = getSpaces(lengthStr - Len(inputStr),padChar) & inputStr  
End Function 

Function Rpad (inputStr, padChar, lengthStr)  
    Rpad = inputStr & getSpaces(lengthStr - Len(inputStr), padChar)  
End Function  




Sub changeIP()

  Dim qry
  naQry = "Select * from Win32_NetworkAdapter where AdapterType Like 'Ethernet%'"
'  ncQry = "Select * From Win32_NetworkAdapterConfiguration"
  Set naItems = objWMIService.ExecQuery(naQry) 
'  Set ncItems = objWMIService.ExecQuery(ncQry) 
  wscript.echo vbCrLf & "------------------------------"
  wscript.echo "Available Ethernet Connections"
  wscript.echo "------------------------------" & vbCrLf

  Dim i
  i = 0 
  For Each naItem in naItems 
     i = i+1
     
     Dim conID
     conID = ""
     conID = naItem.NetConnectionID 

     If(IsNull(naItem.NetConnectionID)) Then
      conID = ""
     End If

     wscript.echo "[" & i & "]  " & Rpad(conID," ",20) & " (" & naItem.Name & ")" 

  Next

  StdOut.Write vbCrLf & "Select Ethernet Interface : "

  Dim userChoice
  userChoice = StdIn.ReadLine

  if CInt(userChoice) > i Then
  wscript.echo "Invaid choice"
  Exit Sub
  End If

  wscript.echo "You selected : " & naItems.ItemIndex(CInt(userChoice) -1).NetConnectionID & vbCrLf
  StdOut.Write "Set Static IP to : 192.168.1."  
  Dim userIPNumber
  userIPNumber = StdIn.ReadLine

  if CInt(userIPNumber) > 255 Then
  wscript.echo "Cannot be greater than 255"
  Exit Sub
  End If

 wscript.echo "Configuration in progress..."
Set oShell = CreateObject ("WScript.Shell")
Dim myInterface
myInterface = naItems.ItemIndex(CInt(userChoice) - 1).NetConnectionID

Set objExec = oShell.Exec( _
"cmd.exe /C netsh interface ip set address """ & myInterface & """ static 192.168.1." & CInt(userIPNumber) & " 255.255.255.0 192.168.1.2" _
 &  " & netsh int ipv4 set dns name=""" & myInterface &""" static 8.8.8.8 primary validate=no & netsh int ipv4 add dns name=""" & myInterface & """ 8.8.4.4 index=2" _
 )

Do Until objExec.Status
StdOut.Write "|"
    Wscript.Sleep 250

Loop 

wscript.echo vbCrLf &  "Done!"

End Sub


Sub backup()
 
  strComputer = "." 
  Set objWMIService = GetObject("winmgmts:" _ 
      & "{impersonationLevel=impersonate}!\\" & strComputer & "\root\cimv2") 
   
  Set colItems = objWMIService.ExecQuery("Select * from Win32_NetworkAdapter where AdapterType Like 'Ethernet%'") 
  Set i = 0 

  For Each naItem in colItems 
    i = i+1
     wscript.echo "Adapter Type: " & naItem.AdapterType 
    
      Select Case naItem.AdapterTypeID 
          Case 0 strAdapterType = "Ethernet 802.3"  
          Case 1 strAdapterType = "Token Ring 802.5"  
          Case 2 strAdapterType = "Fiber Distributed Data Interface (FDDI)"  
          Case 3 strAdapterType = "Wide Area Network (WAN)"  
          Case 4 strAdapterType = "LocalTalk"  
          Case 5 strAdapterType = "Ethernet using DIX header format"  
          Case 6 strAdapterType = "ARCNET"  
          Case 7 strAdapterType = "ARCNET (878.2)"  
          Case 8 strAdapterType = "ATM"  
          Case 9 strAdapterType = "Wireless"  
          Case 10 strAdapterType = "Infrared Wireless"  
          Case 11 strAdapterType = "Bpc"  
          Case 12 strAdapterType = "CoWan"  
          Case 13 strAdapterType = "1394" 
      End Select 
   
      wscript.echo "Adapter Type Id: " & strAdapterType 
      wscript.echo "AutoSense: " & naItem.AutoSense 
     wscript.echo "Name: " & naItem.Name 
     wscript.echo "Description: " & naItem.Description 
      wscript.echo "Device ID: " & naItem.DeviceID 
      wscript.echo "Index: " & naItem.Index 
      wscript.echo "MAC Address: " & naItem.MACAddress 
      wscript.echo "Manufacturer: " & naItem.Manufacturer 
      wscript.echo "Maximum Number Controlled: " & naItem.MaxNumberControlled 
      wscript.echo "Maximum Speed: " & naItem.MaxSpeed 
      wscript.echo "Net Connection ID: " & naItem.NetConnectionID 
      wscript.echo "Net Connection Status: " & naItem.NetConnectionStatus 
      For Each strNetworkAddress in naItem.NetworkAddresses 
          wscript.echo "NetworkAddress: " & strNetworkAddress 
      Next 
      wscript.echo "Permanent Address: " & naItem.PermanentAddress 
      wscript.echo "PNP Device ID: " & naItem.PNPDeviceID 
      wscript.echo "Product Name: " & naItem.ProductName 
      wscript.echo "Service Name: " & naItem.ServiceName 
      wscript.echo "Speed: " & naItem.Speed 
Next 

End Sub


Call changeIP()
