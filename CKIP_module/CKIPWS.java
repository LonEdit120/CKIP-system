import java.net.Socket;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
 
public class CKIPWS {
	
    private String address;
    private int port;
    private Socket client;
 
    public CKIPWS(String addr, int port) {
        this.address = addr;
        this.port = port;
    }
    
    public ArrayList<Tuple<String, String>> seg(String Sent){
    	
    	ArrayList<Tuple<String, String>> WSResult = new ArrayList<Tuple<String, String>>();
    	
        try {
        	
        	client = new Socket(this.address, this.port);
        	
            //Send Sentence
        	BufferedOutputStream out = new BufferedOutputStream(client.getOutputStream());
            String outmsg = "seg@@" + Sent;
            out.write(outmsg.getBytes(Charset.forName("UTF-8")));
            out.flush();
            //out.close();
            
            //Receive Result
            BufferedInputStream in = new BufferedInputStream(client.getInputStream());
            byte[] b = new byte[1024];
            String msg = "";
            while (in.read(b) > 0)
            	msg += new String(b, Charset.forName("UTF-8"));
            
            out.close();
            in.close();
            client.close();
            
            String[] terms = msg.replace("\n", "").replace("\0", "").split("?");
            for(int i = 0; i < terms.length; i++){
            	String [] temp = terms[i].substring(0, terms[i].length() - 1).split("\\(");
            	Tuple<String, String> pair = new Tuple<String, String>(temp[0], temp[1]); 
            	WSResult.add(pair);
            }
            
        } catch (java.io.IOException e) {
            System.out.println("Socket ???? !");
            System.out.println("IOException :" + e.toString());
        }
        
        return WSResult;
    }
    
    public void destroy(){
    	client = null;
    }
    
    public class Tuple<X, Y> { 
  	  public final X word; 
  	  public final Y pos; 
  	  public Tuple(X x, Y y) { 
  	    this.word = x; 
  	    this.pos = y; 
  	  }
    }
    
    public static void main(String[] args) {
		// TODO Auto-generated method stub
		CKIPWS WS = new CKIPWS("140.116.245.151", 9998);
		ArrayList<CKIPWS.Tuple<String, String>> x = WS.seg("????");
		for(int i = 0; i < x.size(); i++){
			System.out.print(x.get(i).word + ' ' + x.get(i).pos + ' ');
		}
	}
    
}

